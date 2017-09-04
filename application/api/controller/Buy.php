<?php
namespace app\api\controller;

use think\Request;

/**
 * 购买订单 * 服务 * 操作方法
 *
 */
class Buy
{
    use \app\core\traits\ProviderFactory;

    private     $p_auth;

    /***
     * 注入依赖
     *
     */
    public function __construct(
        Request                         $request,
        \app\core\provider\Auth         $p_auth,
        \app\core\provider\Orders       $p_order,
        \app\core\model\Orders          $m_order
    )
    {
        //验证授权合法
        $p_auth->check($request, [
            'public' => ['*'],
            'private'=> []
        ]);

        //授权服务
        $this->p_auth   = $p_auth;


        //订单服务
        $this->p_order  = $p_order;

        //订单模型
        $this->m_order  = $m_order;

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
        $shopid         = input('param.shop_id/d');

        //接收 - 订单数据包
        $order_info     = input('param.order/s');
        if (!$this->is_json($order_info)){
            return jsonData(0, 'order 数据不合法');
        }

        //转换数组
        $info = json_decode($order_info, true);

        $info['is_first']       = !isset($info['is_first']) ? 1 : intval($info['is_first']);
        $info['first_money']    = 5;
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
        $info['ordersn']        = !isset($info['ordersn']) ? '' : trim($info['ordersn']);

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

        //测试支付
        $info['pay_price']      = 0.01;

        $offset_money           =  $info['offset_money'];

        //向微信发送预订单
        $wechat         = new \app\core\provider\WeChat();

        //生成 - 订单号
        $ordersn        = $this->p_order->getOrderSN();


        //付款 - 单位转换
        $pay_price      = floatval($info['pay_price'] * 100);  
        $body           = "点餐订单, 总价:{$pay_price},红包抵扣:{$offset_money}";
        $result         = $wechat->payment($ordersn, $openid, $body, $pay_price);

        //$deskid         = 10;   //$desk_sn;

        
        //本地 - 订单信息
        $orderinfo      = array(

            'ordersn'           => $info['ordersn'],                    //是否老订单
            'shop_id'           => $shopid,                             //商户id
            'user_id'           => $userid,                             //顾客id

            'desk_sn'           => $info['desk_sn'],                    //桌位编号
            'user_count'        => $info['user_count'],                 //就餐人数


            'is_first'          => $info['is_first'],                   //首次消费       0 等于首次消费
            'first_money'       => $info['first_money'],                //首次立减金额

            'mode_rate'         => 0.08,                                //红包比率
            'mode_money'        => $info['must_price'] * 0.08,          //红包金额

            
            //￥ = goods_price
            'total_price'       => $info['total_price'],                //总价
            'coupon_list_id'    => $info['coupon_list_id'],             //优惠卷id     
            'coupon_price'      => $info['coupon_price'],               //优惠金额

            'must_price'        => $info['must_price'],                 //应该支付金额
            'pay_price'         => $info['pay_price'],                  //实际支付金额
            
            'order_rate'        => 0.02,                                //手续费比率
            'order_money'       => $info['order_money'],                //手续费金额

            'offset_money'      => $info['offset_money'],               //使用红包抵扣金额
            'shop_price'        => $info['must_price'],                 //商家实际到账金额          

            'goods_price'       => $info['goods_price'],                //商品总价
            'goods_list'        => $info['goods_list'],                 //购物车(商品列表)
            'user_list'         => $info['user_list'],                  //同桌用户

            'message'           => $info['message'],                    //给商家留言
            'remark'            => $info['remark'],                     //口味备注

            'pay_way'           => $info['pay_way'],                    //支付方式
            'pay_time'          => 0,                                   //支付完成时间


            'created'           => time(),
            'updated'           => time()
        );

        $result['order']        = $this->p_order->initOrderData($ordersn, $shopid, $userid, $info['desk_sn'], $orderinfo);

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
                $result = $this->p_order->endOrderStatus($order_info, $post_data);
                if ($result) {

                    $printer    = new \app\core\provider\BotPrinter();

                    $bot_sn     = ($order_info['message']) ? $bot_arr[$order_info['message']] : '';
                    $printer->getWords($bot_sn);

                    //5台同时打
                    $printer->getWordsChip();

                    //启动打印机(队列版)
                    if ($openid == 'opkjx0CFj1yEKskVzhmzXVHB3daY') {
                        
                    }

                    //2号  -- 殷宏华
                    if ($openid == 'opkjx0L3kMBBrU413UHLyTyE_4is') {
                        //$printer->getWords('217502989');


                    }else{
                        //$printer->printOrderInfo($order_info, $post_data);    
                    }

                    //返回微信通知
                    $wechat->return_success();

                }else{
                    echo  '结束订单事务失败';
                }
            }

            echo '订单信息验证失败';
        }
    }



    //是否新顾客
    public function isFirst()
    {
        $arr = ['offset_money'=>1, 'is_first'=>0, 'first_money'=>6, 'coupon'=>[
            'coupon_list_id'=>0, 'status'=>1, 'get_time'=>0, 'use_time'=>0, 'u_status'=>0,'dis_price'=>0.00, 'start_time'=>0, 'end_time'=>0, 'conditon'=>''], 
        'order_rate'=>0.02,'mode_rate'=>0.08, 
        'pay_type'=>[
            ['typeid'=>0, 'title'=>'在线支付', 'is_default'=>1], ['typeid'=>1, 'title'=>'现金支付', 'is_default'=>0]], 
        'remark'=>[['txt'=>'', 'color'=>''], ['txt'=>'', 'color'=>'']], 'message'=>'：给买家留言,特殊要求。', 'user_list'=>[
        ['avatar'=>'', 'userid'=>1, 'sex'=>0, 'nickname'=>'用户1'],['avatar'=>'', 'userid'=>2, 'sex'=>0, 'nickname'=>'用户2'],['avatar'=>'', 'userid'=>3, 'sex'=>1, 'nickname'=>'用户3']
        ], 'use_base'=>[
            ['id'=>1, 'name'=>'餐具', 'price'=>2.00, 'img_url'=>'', 'cate_id'=>2, 'num'=>0], ['id'=>2, 'name'=>'纸巾', 'price'=>1.00, 'img_url'=>'', 'cate_id'=>2, 'num'=>2]
        ]];

        return jsonData(1, 'ok', $arr);
    }



    public function printOrder()
    {
        $print_bot_sn = '217502439';

        //启动打印机(测试版)
        $printer    = new \app\core\provider\BotPrinter();
        $printer->getWords($print_bot_sn);
       
        //$this->getLine();



    }

    private function getLine()
    {
        $str = '西红柿鸡蛋炒饭';
        //$str = '123PHPp我们00我';

		//$orderInfo .= '饭　　　　　 　10.0   10  10.0<BR>';
		//$orderInfo .= '饭　　　　　 　               <BR>';

        print $this->_strlen($str);
    }

    function _strlen($str)  
    {  
        return (strlen($str) + mb_strlen($str, 'UTF8')) / 2; 
    }  

}