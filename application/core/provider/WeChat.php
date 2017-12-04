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
            print_r($data);exit;

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

        $access_token = $this->asscessToken();
        $width = 430;

        $scene = 'shop_id='.$shop_id.'&bagid='.$bagid;

        $path = "pages/speakVoice/speakVoice?$scene";

//        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=$access_token";
        $url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=$access_token";

        $data = [
            'path'      => $path,
            'width'     => $width,
        ];

        $post_data = json_encode($data);

        $result= api_notice_increment($url,$post_data);     //获取微信小程序二维码

        $urls = 'picture/code/' . date('Ymd', time()) . '/';
        $PATH = ROOT_PATH . 'Uploads/picture/code/' . date('Ymd', time()) . '/';

        if (!is_dir($PATH)) {
            mkdir($PATH, 0777, true);
        }//判断目录是否存在，不存在则创建
        $name = md5(rand(100, 999) . time());
        $imgPath = $PATH . $name . $shop_id . '.png';

        file_put_contents($imgPath, $result);       //保存图片

        $paths = $urls . $name . $shop_id . '.png';

        return $paths;
    }


    /***
     * 生成带参小程序码测试
     *
     * @param   int   $shop_id       商户id
     *
     */

    public function codeTest($shop_id,$desk_sn){


        $access_token = $this->asscessToken();
        $width = 430;

        $scene = 'shop_id='.$shop_id.'&desk_sn='.$desk_sn;

        $path = "pages/menu/menu?$scene";

//        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=$access_token";
        $url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=$access_token";

        $data = [
            'path'      => $path,
            'width'     => $width,
        ];

        $post_data = json_encode($data);

        $result= api_notice_increment($url,$post_data);     //获取微信小程序二维码

        $urls = 'picture/code/' . date('Ymd', time()) . '/';
        $PATH = ROOT_PATH . 'Uploads/picture/code/' . date('Ymd', time()) . '/';

        if (!is_dir($PATH)) {
            mkdir($PATH, 0777, true);
        }//判断目录是否存在，不存在则创建

        if (preg_match("/[\x7f-\xff]/", $desk_sn)) {
            $imgPath = $PATH . 'zhongwen.jpeg';
            file_put_contents($imgPath, $result);       //保存图片

            $paths = $urls . 'zhongwen.jpeg';
        }else{

            $imgPath = $PATH . $desk_sn . '.jpeg';
            file_put_contents($imgPath, $result);       //保存图片

            $paths = $urls . $desk_sn . '.jpeg';
        }



        return $paths;
    }

    //获取access_token
    public function asscessToken(){
        $token_access_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . APPID . "&secret=" . SECRET;
        $res = file_get_contents($token_access_url); //获取文件内容或获取网络请求的内容
        //echo $res;
        $result = json_decode($res, true); //接受一个 JSON 格式的字符串并且把它转换为 PHP 变量
        $access_token = $result['access_token'];
        return $access_token;
    }

}