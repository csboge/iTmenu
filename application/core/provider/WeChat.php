<?php
namespace app\core\provider;

//小程序授权
define('APPID', 'wx3fcef4db43bcfaed');	                //*必填*：小程序唯一标识
define('SECRET', '1354bdaf7a9e13b5fe97c22de97b90b3');	//*必填*: 小程序的 app secret

//以下参数不需要修改
define('IP', 'ssl://api.weixin.qq.com');			    //接口IP或域名
define('PORT', 443);						            //接口IP端口


/**
 * 微信方面 * 服务 * 操作方法
 *
 *
 *
 */
class WeChat
{

    public function __construct()
    {
        //初始化 **请求工具
        $this->client = new \app\core\provider\HttpClient(IP, PORT);
    }
    


    /**
     * 获得用户授权(unionid / openid)
     *
     * @param   string   $jscode    客户端登录code
     *
     */
    function getSessionKey($jscode)
    {
        $content = array(			
            'appid'     => APPID,
            'secret'    => SECRET,
            'js_code'   => $jscode,
            'grant_type'=> 'authorization_code'
        );

        $path = '/sns/jscode2session';
        if(!$this->client->get($path, $content)){
            return false;

        }else{
            $json = $this->client->getContent();
            return json_decode($json, true);
        }
    }




}