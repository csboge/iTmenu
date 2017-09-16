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
    function getSessionKey($jscode, $shopid = 0)
    {

        /**
         * 初始请求
         *
         */
        $host    = 'ssl://api.weixin.qq.com'; 
        $port    = 443;
        $client  = new \app\core\provider\HttpClient($host, $port);

        $app_id  = ($shopid > 0) ? 'wx22447b890ae00ce7' : APPID;

        //业务参数
        $content = array(			
            'appid'     => $app_id,
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

            if (isset($data['errcode'])) {
                return false;
            }

            return $data;
        }
    }


    /***
     * 预支付请求
     *
     */
    function payment($ordersn, $openid, $body, $total_fee, $shopid = 0)
    {
        $app_id  = ($shopid > 0) ? 'wx22447b890ae00ce7' : APPID;
        $weixinpay      = new \app\core\provider\WeixinPay($app_id, $openid, MCHID, SIGNKEY, $ordersn, $body, $total_fee);  
        $result         = $weixinpay->pay(); 

        return $result;
    }


    /***
     * 支付回调 - 签名验证
     *
     * @param   string   $post_sign    表单签名
     * @param   string   $post_data    表单数据
     *
     */
    public function checkSign($post_sign, $post_data, $shopid = 0)
    {
        $app_id  = ($shopid > 0) ? $post_data['appid'] : APPID;
        $weixinpay      = new \app\core\provider\WeixinPay($app_id, NULL, MCHID, SIGNKEY, NULL, NULL, NULL);  

        unset($post_data['sign']);
        $my_sign = $weixinpay->getSign($post_data);

        return ($my_sign === $post_sign) ? true : false;
    }


    /*
     * 给微信发送确认订单金额和签名正确，SUCCESS信息 -xzz0521
     */
    public function return_success(){
        $return['return_code'] = 'SUCCESS';
        $return['return_msg'] = 'OK';
        $xml_post = '<xml>
                    <return_code>'.$return['return_code'].'</return_code>
                    <return_msg>'.$return['return_msg'].'</return_msg>
                    </xml>';
        echo $xml_post;
        exit;
    }
}