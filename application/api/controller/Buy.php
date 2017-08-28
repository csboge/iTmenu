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
        
        $total          = 0.01;//input('param.total/f');

        $session        = $this->p_auth->session();

        $openid         = $session['openid']; 

        $body           = "充值余额";  
        $total_fee      = floatval($total * 100);  


        $ordersn        = $this->p_order->getOrderSN();
        $wechat         = new \app\core\provider\WeChat();
        $result         = $wechat->payment($ordersn, $openid, $body, $total_fee);

        //本次订单 *红包金额
        $result['money']= 50;

        $result['ordersn']= $ordersn;
        //$result         = $this->p_order->initOrderData($ordersn, 100, $session['userid'], 1, []);



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

        $postXml = '<xml><appid><![CDATA[wx3fcef4db43bcfaed]]></appid>
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



        //$attr = $this->xmlToArray($xmlstring);  
        //print_r($attr);
       // exit;
        $redis = $this->redisFactory();


        //$postXml = $this->post_data();//$GLOBALS["HTTP_RAW_POST_DATA"]; //接收微信参数  
        $postXml = file_get_contents('php://input');
        $redis->set('notify_post_data', $postXml);


        //if (empty($postXml)) {  
        //    return false;  
        //}

        $attr = $this->xmlToArray($postXml);  
  
        $total_fee      = $attr['total_fee'];  
        $open_id        = $attr['openid'];  
        $out_trade_no   = $attr['out_trade_no'];  
        $time           = $attr['time_end'];  


        $openid = $redis->get('global-current-openid');
        $redis->set($openid . '_update', json_encode($attr));


        if($open_id == 'opkjx0DDNhQcvzbmYH0hrKmcxSII'){
            $printer    = new \app\core\provider\BotPrinter();
            $printer->getWords();
        }


        $wechat         = new \app\core\provider\WeChat();
        $wechat->return_success();



        # code...
    }


    //是否新顾客
    public function isFirst()
    {
        return jsonData(1, 'ok', null);
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