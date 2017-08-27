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
            'public' => [],
            'private'=> ['*']
        ]);

        //授权服务
        $this->p_auth   = $p_auth;


        //订单服务
        $this->p_order  = $p_order;

        //订单模型
        $this->m_order  = $m_order;

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
        
        $total          = input('post.total/f');

        $session        = $this->p_auth->session();

        $openid         = $session['openid']; 

        $body           = "充值余额";  
        $total_fee      = floatval($total * 100);  


        $wechat         = new \app\core\provider\WeChat();
        $result         = $wechat->payment($openid, $body, $total_fee);

        //本次订单 *红包金额
        $result['money']= 50;

        $ordersn        = $this->p_order->getOrderSN();
        $result         = $this->p_order->initOrderData($ordersn, 100, $session['userid'], 1, []);



        $redis = $this->redisFactory();
        $redis->set($openid, json_encode($result));

        $redis->set('global-current-openid', $openid);


        return jsonData(1, 'ok', $result);

    }


    //微信支付 回调
    public function notify()
    {
        $postXml = $GLOBALS["HTTP_RAW_POST_DATA"]; //接收微信参数  
        //if (empty($postXml)) {  
        //    return false;  
        //}

        $attr = xmlToArray($postXml);  
  
        $total_fee      = $attr[total_fee];  
        $open_id        = $attr[openid];  
        $out_trade_no   = $attr[out_trade_no];  
        $time           = $attr[time_end];  


        $redis = $this->redisFactory();
        $openid = $redis->get('global-current-openid');
        $redis->set($openid . '_update', json_encode($attr));



        $printer    = new \app\core\provider\BotPrinter();
        $printer->getWords();


        $wechat         = new \app\core\provider\WeChat();
        $wechat->return_success();



        # code...
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