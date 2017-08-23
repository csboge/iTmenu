<?php
namespace app\core\provider;

//小程序授权
define('APPID', 'wx3fcef4db43bcfaed');	                //*必填*：小程序唯一标识
define('SECRET', '1354bdaf7a9e13b5fe97c22de97b90b3');	//*必填*: 小程序的 app secret


//支付授权
define('MCHID', '1487245952');	                        //*必填*: 商户号
define('SIGNKEY', 'csboge1073payKEY2913epoqiwpemans');	//*必填*: 密钥



//以下参数不需要修改
//define('IP', 'ssl://api.weixin.qq.com');			    //接口IP或域名
//define('PORT', 443);						            //接口IP端口


/**
 * 微信方面 * 服务 * 操作方法
 *
 *
 *
 */
class WeChat
{

    public function __construct(){}
    


    /**
     * 获得用户授权(unionid / openid)
     *
     * @param   string   $jscode    客户端登录code
     *
     */
    function getSessionKey($jscode)
    {

        /**
         * 初始请求
         *
         */
        $host    = 'ssl://api.weixin.qq.com'; 
        $port    = 443;
        $client  = new \app\core\provider\HttpClient($host, $port);

        //业务参数
        $content = array(			
            'appid'     => APPID,
            'secret'    => SECRET,
            'js_code'   => $jscode,
            'grant_type'=> 'authorization_code'
        );

        $path = '/sns/jscode2session';
        if(!$client->get($path, $content)){
            return false;

        }else{
            $json = $client->getContent();
            $data = json_decode($json, true);

            /*if (isset($data['errcode'])) {
                return false;
            }*/

            return $data;
        }
    }


    /***
     * 预支付请求
     *
     */
    function payment($openid, $body, $total_fee)
    {

        $out_trade_no   = MCHID . time();      
        $weixinpay      = new \app\core\provider\WeixinPay(APPID, $openid, MCHID, SIGNKEY, $out_trade_no, $body, $total_fee);  
        $result         = $weixinpay->pay(); 

        return $result;

        /**
         * 初始请求
         *
         */
        /*$host    = 'ssl://api.mch.weixin.qq.com'; 
        $port    = 443;
        $client  = new \app\core\provider\HttpClient($host, $port);


        $content = array(
            'appid'             => APPID,
            'mch_id'            => MCHID,
            'nonce_str'         => MD5(time() + rand(10000, 99999)),
            'body'              => 'JSAPI支付测试',
            'out_trade_no'      => 'SN150848529131073',
            'total_fee'         => 0.01,
            'spbill_create_ip'  => '47.93.97.136',
            'notify_url'        => 'https://api.ai-life.me/api/Buy/notify/',
            'trade_type'        => 'JSAPI',
            'openid'            => 'opkjx0OfG53ZhOpEj-VWqpN_MxR0'
        );


        //签名
        $content['sign'] = $this->getSign($content);

        //return $content;

        $path    = '/pay/unifiedorder';
        if(!$client->get($path, $content)){
            return $content;

        }else{
            $json = $client->getContent();
            $content['result'] = $json;
            return $content;//json_decode($json, true);
        }*/
    }



    /**
     * 数字签名
     *
     */
    private function getSign($data)
    {
        $keys = [];
        foreach($data as $k=>$v){
            if (trim($v)) { $keys[] = $k; }
        }
        array_multisort($keys);


        $arr = [];
        foreach($keys as $k=>$v){
            $arr[] = $v . '=' . $data[$v]; 
        }

        $stringA        = implode('&', $arr);
        $stringSignTemp = $stringA . "&key=" . SIGNKEY;         //注：key为商户平台设置的密钥key
        $sign           = strtoupper(MD5($stringSignTemp));     //注：MD5签名方式

        return $sign;
    }
}