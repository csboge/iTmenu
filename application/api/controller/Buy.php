<?php
namespace app\api\controller;

use think\Request;
use \app\core\traits\ProviderFactory;
/**
 * 购买订单 * 服务 * 操作方法
 *
 *
 *
 */
class Buy
{
    private     $p_auth;

    /***
     * 注入依赖
     *
     */
    public function __construct(
        Request                         $request,
        \app\core\provider\Auth         $p_auth
    )
    {
        //验证授权合法
        $p_auth->check($request, [
            'public' => [],
            'private'=> ['*']
        ]);

        //授权服务
        $this->p_auth   = $p_auth;

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


        $redis = $this->redisFactory();
        $redis->set($openid, json_encode($result));


        return jsonData(1, 'ok', $result);

    }


    //微信支付 回调
    public function notify()
    {
        $postXml = $GLOBALS["HTTP_RAW_POST_DATA"]; //接收微信参数  
        if (empty($postXml)) {  
            return false;  
        }

        $attr = xmlToArray($postXml);  
  
        $total_fee      = $attr[total_fee];  
        $open_id        = $attr[openid];  
        $out_trade_no   = $attr[out_trade_no];  
        $time           = $attr[time_end];  


        $redis = $this->redisFactory();
        $redis->set($openid . '_update', json_encode($attr));


        $wechat         = new \app\core\provider\WeChat();
        $wechat->return_success();

        //$printer    = new \app\core\provider\BotPrinter();
        //$printer->getWords();


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