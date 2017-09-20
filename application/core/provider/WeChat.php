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
    private     $p_auth;

    public function __construct(){

        //授权服务
        //订单模型
        $this->p_auth = new \app\core\provider\Auth();
    }
    


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
    function payment($ordersn, $openid, $body, $total_fee)
    {
        $weixinpay      = new \app\core\provider\WeixinPay(APPID, $openid, MCHID, SIGNKEY, $ordersn, $body, $total_fee);
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
    public function checkSign($post_sign, $post_data)
    {
        $weixinpay      = new \app\core\provider\WeixinPay(APPID, NULL, MCHID, SIGNKEY, NULL, NULL, NULL);

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

    /***
     * 生成带参小程序码
     *
     * @param   int   $shop_id       商户id
     *
     */

    public function code($shop_id,$bagid){

        $session        = $this->p_auth->session();
        return $session;
        $date = json_encode($session);
        my_log('orders',$date,'wechat/code',0,'新用户验证不通过');
        $access_token = $session['access_token'];

        $path = "https://demo.ai-life.me";
        $width = 430;
        $map  = [
            'shop_id'   => $shop_id,
            'bagid'     => $bagid
        ];
        $scene = json_encode($map);

        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=$access_token";

        $data = [
            'path'      => $path,
            'width'     => $width,
            'scene'     => $scene
        ];

        $post_data = json_encode($data);


        $result= api_notice_increment($url,$post_data);

        $url = 'picture/code/' . date('Ymd', time()) . '/';
        $PATH = ROOT_PATH . 'Uploads/picture/code/' . date('Ymd', time()) . '/';

        if (!is_dir($PATH)) {
            mkdir($PATH, 0777, true);
        }//判断目录是否存在，不存在则创建
        $name = md5(rand(100, 999) . time());
        $imgPath = $PATH . $name . '.jpeg';
        file_put_contents($imgPath, $result);

        $paths = $url . $name . '.jpeg';

        return $paths;
    }

}