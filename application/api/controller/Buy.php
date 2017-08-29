<?php
namespace app\api\controller;

use think\Request;

/**
 * 购买订单 * 服务 * 操作方法
 *
 *
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
        $desk_sn        = input('param.desk_sn/s');

        if ($desk_sn) {
            return jsonData(0, 'desk_sn 桌位不能为空');
        }

        //本地订单
        $info                   = $_POST;
        $info['is_first']       = !isset($info['is_first']) ? 1 : intval($info['is_first']);
        $info['first_money']    = 5;
        $info['coupon_list_id'] = !isset($info['coupon_list_id']) ? 0 : intval($info['coupon_list_id']);
        $info['coupon_price']   = !is_numeric($info['coupon_price']) ? 0 : $info['coupon_price'];

        $info['must_price']     = !is_numeric($info['must_price']) ? 0 : $info['must_price'];
        $info['pay_price']      = !is_numeric($info['pay_price']) ? 0 : $info['pay_price'];

        $info['order_money']    = !is_numeric($info['order_money']) ? 0 : $info['order_money'];
        $info['offset_money']   = !is_numeric($info['offset_money']) ? 0 : $info['offset_money'];

        $info['shop_price']     = !is_numeric($info['shop_price']) ? 0 : $info['shop_price'];
        $info['goods_price']    = !is_numeric($info['goods_price']) ? 0 : $info['goods_price'];
        $info['pay_way']        = !is_numeric($info['pay_way']) ? 0 : intval($info['pay_way']);

        
        $info['goods_list']     = !isset($info['goods_list']) ? '' : trim($info['goods_list']);
        $info['user_list']      = !isset($info['user_list']) ? '' : trim($info['user_list']);
        $info['message']        = !isset($info['message']) ? '' : trim($info['message']);
        $info['remark']         = !isset($info['remark']) ? '' : trim($info['remark']);

        $total          = input('param.total_price/f');
        $offset_money   = input('param.offset_money/f');

        if ($desk_sn) {
            return jsonData(0, 'desk_sn 桌位不能为空');
        }

        //向微信发送预订单
        $wechat         = new \app\core\provider\WeChat();

        //生成 - 订单号
        $ordersn        = $this->p_order->getOrderSN();


        //付款 - 单位转换
        $total_fee      = floatval($total * 100);  
        $body           = "点餐订单, 总价:{$total_fee},红包抵扣:{$offset_money}";
        $result         = $wechat->payment($ordersn, $openid, $body, $total_fee);

        $deskid         = 10;//$desk_sn;

        
        //本地 - 订单信息
        $orderinfo      = array(

            'shop_id'           => $shopid,                             //商户id
            'user_id'           => $userid,                             //顾客id
            'desk_id'           => $deskid,                             //桌位id

            'is_first'          => $info['is_first'],                   //首次消费       0 等于首次消费
            'first_money'       => $info['first_money'],                //首次立减金额

            //￥ = goods_price
            'total_price'       => $total_fee,                          //总价
            'coupon_list_id'    => $info['coupon_list_id'],             //优惠卷id     
            'coupon_price'      => $info['coupon_price'],               //优惠金额

            'must_price'        => $info['must_price'],                 //应该支付金额
            'pay_price'         => $info['pay_price'],                  //实际支付金额
            
            'order_rate'        => 0.02,                                //手续费比率
            'order_money'       => $info['order_money'],                //手续费金额

            'offset_money'      => $info['offset_money'],               //使用红包抵扣金额
 
            'shop_price'        => $info['shop_price'],                 //商家实际到账金额          

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

        $result['order_info']   = $this->p_order->initOrderData($ordersn, $shopid, $userid, $deskid, $orderinfo);


        //本次订单 *红包金额
        $result['money']= 50;

        $result['ordersn']= $ordersn;

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


        $xmlstring = '<xml><appid><![CDATA[wx3fcef4db43bcfaed]]></appid>
<bank_type><![CDATA[CFT]]></bank_type>
<cash_fee><![CDATA[1]]></cash_fee>
<fee_type><![CDATA[CNY]]></fee_type>
<is_subscribe><![CDATA[N]]></is_subscribe>
<mch_id><![CDATA[1487245952]]></mch_id>
<nonce_str><![CDATA[199dvzwzdizvbnusk14921jfi64xrx4w]]></nonce_str>
<openid><![CDATA[opkjx0DDNhQcvzbmYH0hrKmcxSII]]></openid>
<out_trade_no><![CDATA[14872459521503829787]]></out_trade_no>
<result_code><![CDATA[SUCCESS]]></result_code>
<return_code><![CDATA[SUCCESS]]></return_code>
<sign><![CDATA[C69E710074E268A5E5E4770FFE785761]]></sign>
<time_end><![CDATA[20170827182954]]></time_end>
<total_fee>1</total_fee>
<trade_type><![CDATA[JSAPI]]></trade_type>
<transaction_id><![CDATA[4008152001201708278572138879]]></transaction_id>
</xml>';

        $xmlstring = file_get_contents('php://input');
        if (empty($xmlstring)) {  
            return false;  
        }

        //$redis = $this->redisFactory();

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

            $session        = $this->p_auth->session();

            //查询订单
            $order_info            = $this->m_order->getOrderForSN($ordersn);
            if(!$order_info) { return false; }

            //订单状态
            if ($order_info['status'] == 1) {
                $wechat->return_success();


            //验证： 订单支付金额 -- 订单状态 -- 用户id
            $is_pay_price = ($order_info['pay_price'] == $pay_price) ? true : false;
            $is_user_id   = ($order_info['user_id'] == $session['userid']) ? true : false;

            } else if ($is_pay_price && $is_user_id && $order_info['status'] == 0) {


                //结束订单(事务处理)
                $result = $this->p_order->endOrderStatus($order_info, $post_data);
                if ($result) {

                    //启动打印机(队列版)
                    $printer    = new \app\core\provider\BotPrinter();
                    $printer->printOrderInfo($order_info, $post_data, $session);

                    //返回微信通知
                    $wechat->return_success();
                }
            }
        }
    }



    //是否新顾客
    public function isFirst()
    {
        return jsonData(1, 'ok', ['is_first'=>0, 'first_money'=>5]);
    }



    public function getOrder()
    {
        $openid          = input('get.openid');


        $redis = $this->redisFactory();
        print $redis->get($openid);

        print '---------';
        print $redis->get($openid . '_update');
    }

}