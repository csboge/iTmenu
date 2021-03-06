<?php
namespace app\api\controller;

use think\Request;
use think\Db;

/**
 * 购买订单 * 服务 * 操作方法
 *
 */
class Buy
{
    use \app\core\traits\ProviderFactory;

    private     $p_auth;
    private     $request;

    /***
     * 注入依赖
     *
     */
    public function __construct(
        Request                         $request,
        \app\core\provider\Auth         $p_auth,
        \app\core\provider\Orders       $p_order,
        \app\core\model\Orders          $m_order,
        \app\core\model\Shop            $m_shop,
        \app\core\model\Coupon          $m_coupon,
        \app\core\model\User            $m_user,
        \app\core\model\CouponList      $m_couponlist,
        \app\core\model\Goods           $m_goods,
        \app\core\model\Tistics         $m_tistics
    )
    {
        //验证授权合法
        $p_auth->check($request, [
            'public' => ['notify'],
            'private'=> []
        ]);

        //授权服务
        $this->p_auth   = $p_auth;


        //订单服务
        $this->p_order  = $p_order;

        //订单模型
        $this->m_order  = $m_order;

        //商户信息模型
        $this->m_shop   = $m_shop;

        //优惠券模型
        $this->m_coupon = $m_coupon;

        //用户模型
        $this->m_user   = $m_user;

        //用户优惠券模型
        $this->m_couponlist = $m_couponlist;

        //商品模型
        $this->m_goods = $m_goods;

        //获取当前控制器名
        $this->request = \think\Request::instance();

        //商户收入统计模型
        $this->m_tistics = $m_tistics;

    }

    public function demoData()
    {

        $redis = $this->redisFactory();
        $openid = $redis->get('global-current-openid');


        echo '--update: ' . $redis->get($openid . '_update');


        echo '--init:' . $redis->get($openid);

        echo '--notify_post_data:' .  $redis->get('notify_post_data');
    }


    /**
     * 支付订单
     *
     *
     */
    public function submitOrderPay()
    {
        $ordersn          = input('param.ordersn/s');
        if(!$ordersn) {
            return jsonData(0, '订单号不能为空');
        }

        //查询订单
        $row            = $this->m_order->getOrderForSN($ordersn);
        if(!$row) {
            return jsonData(-1, '订单不存在');
        }

        $session        = $this->p_auth->session();

        $openid         = $session['openid'];       
        $body           = "充值余额";  
        $total_fee      = floatval($total * 100);  

        $wechat         = new \app\core\provider\WeChat();
        $result         = $wechat->payment($openid, $body, $total_fee);

        //本次订单 *红包金额
        $result['money']= 50;

        return jsonData(1, 'ok', $result);
    }  

    function is_json($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }


    /**
     * 提交订单
     *
     *
     */
    public function submitOrder()
    {
        //用户信息
        $session        = $this->p_auth->session();
        $openid         = $session['openid'];
        $userid         = $session['userid'];
        //$shopid         = input('param.shop_id/d');

        $shopid = intval($this->p_auth->getShopId());

        //接收 - 订单数据包
        $order_info     = input('param.order/s');
        if (!$this->is_json($order_info)){
            return jsonData(0, 'order 数据不合法');
        }

        //当前模块控制器方法
        $action_name= $this->request->module().DS.$this->request->controller().DS.$this->request->action();

        //生成 - 订单号
        $ordersn        = $this->p_order->getOrderSN();

        //转换数组
        $info = json_decode($order_info, true);



        $info['is_first']       = !isset($info['is_first']) ? 1 : intval($info['is_first']);
        $info['first_money']    = !isset($info['first_money']) ? 0 : intval($info['first_money']);//5;
        $info['coupon_list_id'] = !isset($info['coupon_list_id']) ? 0 : intval($info['coupon_list_id']);
        $info['coupon_price']   = !is_numeric($info['coupon_price']) ? 0 : $info['coupon_price'];


        $info['must_price']     = !is_numeric($info['must_price']) ? 0 : $info['must_price'];
        $info['pay_price']      = !is_numeric($info['pay_price']) ? 0 : $info['pay_price'];

        $info['order_money']    = !is_numeric($info['order_money']) ? 0 : $info['order_money'];
        $info['offset_money']   = !is_numeric($info['offset_money']) ? 0 : $info['offset_money'];

        //$info['shop_price']     = !is_numeric($info['shop_price']) ? 0 : $info['shop_price'];
        $info['goods_price']    = !is_numeric($info['goods_price']) ? 0 : $info['goods_price'];
        $info['pay_way']        = !is_numeric($info['pay_way']) ? 0 : intval($info['pay_way']);

        
        $info['goods_list']     = !isset($info['goods_list']) ? '' : trim($info['goods_list']);
        $info['user_list']      = !isset($info['user_list']) ? '' : trim($info['user_list']);
        $info['message']        = !isset($info['message']) ? '' : trim($info['message']);
        $info['remark']         = !isset($info['remark']) ? '' : trim($info['remark']);

        $info['total_price']    = !is_numeric($info['total_price']) ? 0 : $info['total_price'];
        $info['order_sn']        = !isset($info['order_sn']) ? $ordersn : trim($info['order_sn']);

        //桌位
        $info['desk_sn']        = !isset($info['desk_sn']) ? '' : trim($info['desk_sn']);

        //就餐人数
        $info['user_count']     = !isset($info['user_count']) ? 0 : intval($info['user_count']);

        if (!$info['desk_sn']) {
            return jsonData(0, '请填写桌位编码');
        }

        if ($info['user_count'] <= 0) {
            return jsonData(0, '请填写就餐人数');
        }

        //商户是否上线
        $isOnline = $this->m_shop->isOnline($shopid);
        if($isOnline['status'] == 0){
            //测试支付
            $info['pay_price']      = 0.01;
        }

        $offset_money           =  $info['offset_money'];

        //向微信发送预订单
        $wechat         = new \app\core\provider\WeChat();




        //付款 - 单位转换
        $pay_price      = floatval($info['pay_price'] * 100);  
        $body           = "点餐订单, 总价:{$pay_price},红包抵扣:{$offset_money}";
        $result         = $wechat->payment($ordersn, $openid, $body, $pay_price);

        //$deskid         = 10;   //$desk_sn;

        /**
         * 订单信息验证
         */

        if($info['is_first']) {
            //新用户验证
            $count = $this->m_order->isFirstCons($shopid, $userid);
            if ($info['is_first'] > 0 && $count > 0) {
                return jsonData(0, '您不是首次消费哦', $count);
            }

            //首次立减金额验证
            $first_money = $this->m_shop->isShopMoney($shopid);

            if ($info['is_first'] > 0 && $info['first_money'] != $first_money['is_first']) {
                return jsonData(0, '首次立减金额不对', $first_money);
            }

        }
        if($info['coupon_list_id'] != 0 && $info['coupon_price'] != 0){
            //优惠券是否存在
            $coupon_price   = $this->m_coupon->isCouponPrice($info['coupon_list_id']);
            if(!$coupon_price){
                return jsonData(0, '优惠券不存在',$coupon_price);
            }

            //优惠金额是否正确
            $coupon_money   = $this->m_coupon->isCouponMoney($info['coupon_list_id']);
            if($info['coupon_price'] != $coupon_money['dis_price']){
                return jsonData(0, '优惠金额不对',$coupon_money);
            }

        }

        //红包余额
        $is_money       = $this->m_user->isMoney($userid);


        if($is_money < $info['offset_money']){
            return jsonData(0, '红包余额不够',$is_money);
        }

        $mode_money = ceil(($info['total_price']-$info['coupon_price']-$info['first_money'])*$info['mode_rate']);
        if($mode_money != $info['mode_money']){
            return jsonData(0, '红包发出金额不对',$mode_money);
        }

        //本地 - 订单信息
        $orderinfo      = array(

            'order_sn'          => $info['order_sn'],                    //是否老订单
            'shop_id'           => $shopid,                             //商户id
            'user_id'           => $userid,                             //顾客id

            'desk_sn'           => $info['desk_sn'],                    //桌位编号
            'user_count'        => $info['user_count'],                 //就餐人数


            'is_first'          => $info['is_first'],                   //首次消费       0 等于首次消费
            'first_money'       => $info['first_money'],                //首次立减金额

            'mode_rate'         => $info['mode_rate'],                  //红包比率
            'mode_money'        => $info['mode_money'],                 //红包金额

            
            //￥ = goods_price
            'total_price'       => $info['total_price'],                //总价
            'coupon_list_id'    => $info['coupon_list_id'],             //优惠卷id     
            'coupon_price'      => $info['coupon_price'],               //优惠金额

            'must_price'        => $info['must_price'],                 //应该支付金额
            'pay_price'         => $info['pay_price'],                  //实际支付金额
            
            'order_rate'        => $info['order_rate'],                 //手续费比率
            'order_money'       => $info['order_money'],                //手续费金额

            'offset_money'      => $info['offset_money'],               //使用红包抵扣金额
            'shop_price'        => $info['total_price']-$info['coupon_price']-$info['mode_money'],                 //商家实际到账金额

            'goods_price'       => $info['goods_price'],                //商品总价
            'goods_list'        => $info['goods_list'],                 //购物车(商品列表)
            'user_list'         => $info['user_list'],                  //同桌用户

            'message'           => $info['message'],                    //给商家留言
            'remark'            => $info['remark'],                     //口味备注

            'pay_way'           => $info['pay_way'],                    //支付方式

            'status'            => 0,
            'pay_time'          => 0,
            'created'           => time(),
            'updated'           => time()
        );

        //创建订单
        $result['order']        = $this->p_order->initOrderData($ordersn, $shopid, $userid, $info['desk_sn'], $orderinfo);


        if (!$result['order']) {
            return jsonData(0, '订单 - 创建失败',$result);
        }

        $redis = $this->redisFactory();
        $redis->set($openid, json_encode($result));
        $redis->set('global-current-openid', $openid);

        return jsonData(1, 'ok', $result);

    }


    public function xmlToArray($xml) {


        //禁止引用外部xml实体 
        libxml_disable_entity_loader(true);


        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);


        $val = json_decode(json_encode($xmlstring), true);


        return $val;
    }


    //微信支付 回调
    public function notify()
    {
        /*  微信官方提醒：
         *  商户系统对于支付结果通知的内容一定要做【签名验证】,
         *  并校验返回的【订单金额是否与商户侧的订单金额】一致，
         *  防止数据泄漏导致出现“假通知”，造成资金损失。
         */


        /*$xmlstring = '<xml><appid><![CDATA[wx3fcef4db43bcfaed]]></appid>
<bank_type><![CDATA[CFT]]></bank_type>
<cash_fee><![CDATA[1]]></cash_fee>
<fee_type><![CDATA[CNY]]></fee_type>
<is_subscribe><![CDATA[N]]></is_subscribe>
<mch_id><![CDATA[1487245952]]></mch_id>
<nonce_str><![CDATA[ioxvqyvja6ln4u74qlg68xbnrh8t3hbj]]></nonce_str>
<openid><![CDATA[opkjx0DDNhQcvzbmYH0hrKmcxSII]]></openid>
<out_trade_no><![CDATA[2017082953509897]]></out_trade_no>
<result_code><![CDATA[SUCCESS]]></result_code>
<return_code><![CDATA[SUCCESS]]></return_code>
<sign><![CDATA[4D6080870C994FCDB63A671B42A63299]]></sign>
<time_end><![CDATA[20170829202130]]></time_end>
<total_fee>1</total_fee>
<trade_type><![CDATA[JSAPI]]></trade_type>
<transaction_id><![CDATA[4008152001201708298990992568]]></transaction_id>
</xml>';*/

        $xmlstring = file_get_contents('php://input');
        if (empty($xmlstring)) {  
            return -1;  
        }

        //当前模块控制器方法
        $action= $this->request->module().DS.$this->request->controller().DS.$this->request->action();

        $redis = $this->redisFactory();
        $redis->set('notify_post_data', $xmlstring);

        $post_data = $this->xmlToArray($xmlstring);

        //实际支付金额
        $pay_price      = $post_data['total_fee'];  
        $openid         = $post_data['openid'];
        $ordersn        = $post_data['out_trade_no'];


        $wechat             = new \app\core\provider\WeChat();


        //签名合法
        $post_sign          = $post_data['sign'];
        $checkSign          = $wechat->checkSign($post_sign, $post_data);
        if($post_data['return_code'] =='SUCCESS' && $checkSign){

            //查询订单
            $order_info            = $this->m_order->getOrderForSN($ordersn);

            if(!$order_info) { return 0; }

            //验证： 订单支付金额 -- 订单状态
            $order_pay_price        = floatval($order_info['pay_price'] * 100); 
            $is_pay_price           = ($order_pay_price == $pay_price) ? true : false;

            //订单状态
            if ($order_info['status'] == 1) {
                $wechat->return_success();

            } else if ($is_pay_price && $order_info['status'] == 0) {


                $bot_arr = [
                    'b1' => '217502992',
                    'b2' => '217502989',
                    'b3' => '217502995',
                    'b4' => '217502997',
                    'b5' => '217502994',
                    'b6' => '217502993',
                    'b7' => '217502996',
                    'b8' => '217502991',
                    'b9' => '217502998',
                    'b10' => '217502990',
                    'b0'  => '217502439'
                ];



                //结束订单(事务处理)
                $result = $this->p_order->endOrderStatus($order_info, $post_data);//******
                if ($result) {

                    $printer    = new \app\core\provider\BotPrinter();

                    $bot_sn     = ($order_info['message']) ? $bot_arr[$order_info['message']] : '';
//                    $printer->getWords('217502439');

                    //开启打印机
                    $printer->printOrderInfo($order_info,$post_data);

                    //发送模板消息


                    //5台同时打
                    //$printer->getWordsChip();

                    //启动打印机(队列版)
//                    if ($openid == 'opkjx0CFj1yEKskVzhmzXVHB3daY') {
//
//                    }

                    //2号  -- 殷宏华
//                    if ($openid == 'opkjx0L3kMBBrU413UHLyTyE_4is') {
//                        //$printer->getWords('217502989');
//
//
//                    }else{
//                        //$printer->printOrderInfo($order_info, $post_data);
//                    }

                    //返回微信通知
                    $wechat->return_success();

                }else{
                    echo  '结束订单事务失败';
                }
            }

            echo '订单信息验证失败';
        }
    }


    /***
     * 订单 - 0元支付
     * @参数 order        订单数据包
     * @参数 shop_id      商户id
     */
    public function submitOffs(){
        //用户信息
        $session        = $this->p_auth->session();
        $userid         = $session['userid'];
//        $userid         = input('param.user_id/d');
        $shopid         = input('param.shop_id/d');

        //接收 - 订单数据包
        $order_info     = input('param.order/s');

        if (!$this->is_json($order_info)){
            return jsonData(0, 'order 数据不合法');
        }

        //转换数组
        $info = json_decode($order_info, true);


        $info['is_first']       = !isset($info['is_first']) ? 1 : intval($info['is_first']);
        $info['first_money']    = !isset($info['first_money']) ? 0 : intval($info['first_money']);//5;
        $info['coupon_list_id'] = !isset($info['coupon_list_id']) ? 0 : intval($info['coupon_list_id']);
        $info['coupon_price']   = !is_numeric($info['coupon_price']) ? 0 : $info['coupon_price'];


        $info['must_price']     = !is_numeric($info['must_price']) ? 0 : $info['must_price'];
        $info['pay_price']      = !is_numeric($info['pay_price']) ? 0 : $info['pay_price'];

        $info['order_money']    = !is_numeric($info['order_money']) ? 0 : $info['order_money'];
        $info['offset_money']   = !is_numeric($info['offset_money']) ? 0 : $info['offset_money'];

        //$info['shop_price']     = !is_numeric($info['shop_price']) ? 0 : $info['shop_price'];
        $info['goods_price']    = !is_numeric($info['goods_price']) ? 0 : $info['goods_price'];
        $info['pay_way']        = !is_numeric($info['pay_way']) ? 0 : intval($info['pay_way']);


        $info['goods_list']     = !isset($info['goods_list']) ? '' : trim($info['goods_list']);
        $info['user_list']      = !isset($info['user_list']) ? '' : trim($info['user_list']);
        $info['message']        = !isset($info['message']) ? '' : trim($info['message']);
        $info['remark']         = !isset($info['remark']) ? '' : trim($info['remark']);

        $info['total_price']    = !is_numeric($info['total_price']) ? 0 : $info['total_price'];
        $info['order_sn']        = !isset($info['order_sn']) ? '' : trim($info['order_sn']);


        //桌位
        $info['desk_sn']        = !isset($info['desk_sn']) ? '' : trim($info['desk_sn']);

        //就餐人数
        $info['user_count']     = !isset($info['user_count']) ? 0 : intval($info['user_count']);

        if (!$info['desk_sn']) {
            return jsonData(0, '请填写桌位编码');
        }

        if ($info['user_count'] <= 0) {
            return jsonData(0, '请填写就餐人数');
        }


        //生成 - 订单号
        $ordersn        = $this->p_order->getOrderSN();

        /**
         * 订单信息验证
         */

        if($info['is_first']) {
            //新用户验证
            $count = $this->m_order->isFirstCons($shopid, $userid);
            if ($info['is_first'] > 0 && $count > 0) {
                return jsonData(0, '您不是首次消费哦', $count);
            }

            //首次立减金额验证
            $first_money = $this->m_shop->isShopMoney($shopid);
            if ($info['is_first'] > 0 && $info['first_money'] !== $first_money) {
                return jsonData(0, '首次立减金额不对', $first_money);
            }
        }
        if($info['coupon_list_id'] !== 0 && $info['coupon_price'] !== 0){

            //优惠券是否存在
            $coupon_price   = $this->m_coupon->isCouponPrice($info['coupon_list_id']);
            if(!$coupon_price){
                return jsonData(0, '优惠券不存在',$coupon_price);
            }

            //优惠金额是否正确
            $coupon_money   = $this->m_coupon->isCouponMoney($info['coupon_list_id']);

            if($info['coupon_price'] != $coupon_money){
                return jsonData(0, '优惠金额不对',$coupon_money);
            }

        }

        //红包余额
        $is_money       = $this->m_user->isMoney($userid);
        if($is_money < $info['offset_money']){
            return jsonData(0, '红包余额不够',$is_money);
        }
        $status = $info['pay_way'] === 1 ? 0 : 1;

        //本地 - 订单信息
        $orderinfo      = array(

            'order_sn'          => $ordersn,                           //订单号
            'shop_id'           => $shopid,                             //商户id
            'user_id'           => $userid,                             //顾客id

            'desk_sn'           => $info['desk_sn'],                    //桌位编号
            'user_count'        => $info['user_count'],                 //就餐人数


            'is_first'          => $info['is_first'],                   //首次消费       0 等于首次消费
            'first_money'       => $info['first_money'],                //首次立减金额

            'mode_rate'         => $info['mode_rate'],                  //红包比率
            'mode_money'        => $info['mode_money'],                 //红包金额


            //￥ = goods_price
            'total_price'       => $info['total_price'],                //总价
            'coupon_list_id'    => $info['coupon_list_id'],             //优惠卷id
            'coupon_price'      => $info['coupon_price'],               //优惠金额

            'must_price'        => $info['must_price'],                 //应该支付金额
            'pay_price'         => $info['pay_price'],                  //实际支付金额

            'order_rate'        => $info['order_rate'],                 //手续费比率
            'order_money'       => $info['order_money'],                //手续费金额

            'offset_money'      => $info['offset_money'],               //使用红包抵扣金额
            'shop_price'        => $info['total_price']-$info['coupon_price']-$info['mode_money'],                 //商家实际到账金额

            'goods_price'       => $info['goods_price'],                //商品总价
            'goods_list'        => $info['goods_list'],                 //购物车(商品列表)
            'user_list'         => $info['user_list'],                  //同桌用户

            'message'           => $info['message'],                    //给商家留言
            'remark'            => $info['remark'],                     //口味备注

            'pay_way'           => $info['pay_way'],                    //支付方式

            'status'            => $status,
            'pay_time'          => time(),
            'created'           => time(),
            'updated'           => time()
        );


        $bot_arr = [
            'b1' => '217502992',
            'b2' => '217502989',
            'b3' => '217502995',
            'b4' => '217502997',
            'b5' => '217502994',
            'b6' => '217502993',
            'b7' => '217502996',
            'b8' => '217502991',
            'b9' => '217502998',
            'b10' => '217502990',
            'b0'  => '217502439'
        ];

        $post_data = [
            'transaction_id' => '',
            'time_end' => ''
        ];

        Db::startTrans();

        try {
            //新增订单
            $result['order']        = $this->p_order->initOrderData($ordersn, $shopid, $userid, $info['desk_sn'], $orderinfo);
//            //判断统计表是否有当天的数据
//            $ististics = $this->m_tistics->isTistics($orderinfo['shop_id'])?$this->m_tistics->isTistics($orderinfo['shop_id']):0;
//            $money = $orderinfo['shop_price'];
//            if(!$ististics){
//                //写入数据统计
//                $tistics = $this->m_tistics->insertTistics($orderinfo['shop_id'],$money);
//            }else{
//                //更新数据统计
//                $tistics = $this->m_tistics->updateTistics($ististics['id'],$money);
//            }
//
//            //更新订单入账记录
//            $tistics_id = $this->m_order->upTistics($orderinfo['order_sn'],$orderinfo['user_id'],$tistics);

            if ($orderinfo['offset_money'] !== 0) {
                //修改用户钱包余额
                $user_money = $this->m_user->userMoney($orderinfo['user_id'], $orderinfo['offset_money']);
            } else {
                $user_money = 'a';
            }

            if ($orderinfo['coupon_list_id'] !== 0) {
                //修改用户优惠券使用记录
                $user_coupon = $this->m_couponlist->CouponStatus($orderinfo['user_id'], $orderinfo['coupon_list_id']);
            } else {
                $user_coupon = 'a';
            }


            if ($result['order'] && $user_money !== 0 && $user_coupon !== 0) {
                // 提交事务
                Db::commit();

                $printer    = new \app\core\provider\BotPrinter();

//                    $printer->getWords('217502439');
                $printer->printOrderInfo($result['order'],$post_data);

                my_log('orders',$user_coupon,'api/controller/buy/submitOffs',6,'打印测试2修改用户优惠券使用记录');

                return jsonData(1, 'OK',$result);
            } else {
                // 回滚事务
                Db::rollback();
                //订单错误

                my_log('orders',$ordersn,'api/controller/buy/submitOffs',-1,'执行出错~~事务回滚');

                $this->m_order->error_log($orderinfo['order_sn']);

                return jsonData(0, '出现错误~~事务回滚1','');
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            //订单错误

            my_log('orders',$ordersn,'api/controller/buy/submitOffs',-1,'执行出错~~事务回滚');

            $this->m_order->error_log($ordersn);

            return jsonData(0, '出现错误~~事务回滚2',$orderinfo);
        }

    }



    /***
     * 订单 - 确认支付页
     * @参数 user_id      用户id
     * @参数 shop_id      商户id
     */
    public function isFirst()
    {
        //用户信息
        $session  = $this->p_auth->session();

        //获得商店id
        $shop     = $this->p_auth->getShopId();

        $map = [
            'id' => $session['userid']
        ];

        $res = Db::name('user')->where($map)->field('money')->find();       //红包余额
        $coupon_list = $this->m_couponlist->getCoupon($session['userid'],$shop);                        //查询优惠券

        foreach ($coupon_list as &$volue){
            $coupon = $this->m_coupon->isCoupon($volue['coupon_id']);
            $volue['title'] = $coupon['title'];
            $volue['type'] = $coupon['type'];
            $volue['dis_price'] = $coupon['dis_price'];
            $volue['start_time'] = $coupon['start_time'];
            $volue['end_time'] = $coupon['end_time'];
            $volue['conditon'] = $coupon['conditon'];
        }
        $first = is_first($session['userid'],$shop);                           //新用户立减
        $use_base = $this->m_goods->getBowl($shop);                         //查询餐具
        foreach ($use_base as &$volue){
            $attrs = json_decode($volue['attrs'],true);
            $volue['name'] = $volue['title'];
            $volue['cate_id'] = $volue['cat_id'];
            $volue['img_url'] = ImgUrl($volue['image']);
            $volue['num'] = $attrs[0]['titles']?$attrs[0]['titles']:0;
            $volue['price'] = $volue['price']?$volue['price']:$attrs[0]['prices'];
            unset($volue['title']);
            unset($volue['attrs']);
            unset($volue['cat_id']);
            unset($volue['image']);
        }
        $data = [
            'money' => $res['money']?$res['money']:0,
            'first' => $first,
            'coupon' => $coupon_list,
            'order_rate' => 0.02,
            'mode_rate' => 0.08,
            "pay_type" => [
                [
                "typeid" => 0,
                "title" => "在线支付",
                "is_default" => 1
                ],
                [
                "typeid" => 1,
                "title" => "现金支付",
                "is_default" => 0
                ]
            ],
            "use_base" => $use_base
        ];

        return jsonData(1, 'ok', $data);
    }
}