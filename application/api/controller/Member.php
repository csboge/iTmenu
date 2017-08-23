<?php
namespace app\api\controller;

use think\Request;

class Member
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
            'public' => ['*'],
            'private'=> []
        ]);

        //授权服务
        $this->p_auth   = $p_auth;

    }


    /***
     * 获得验证码
     */
    function getcode()
    {
        /**
         * 验证手机号 / 邮箱 合法性
         * 查询账号是否存在， 不存在即插入保存， 存在即：更新验证码
         * 生成验证码， 发送验证码
         * 
         */
        return jsonData(1, 'ok', null);
    }


    /***
     * 注册账号
     */
    function register()
    {
        /**
         * 验证手机号 / 邮箱 合法性
         * 获得 验证码， 验证合法性
         * 账号，密码  入库后  
         * 执行登录
         */
        
        $data       = $this->p_auth->session();

        return jsonData(1, 'ok', $data);
    }


    /***
     * 执行登录
     */
    function login()
    {
        /*
        $printer    = new \app\core\provider\BotPrinter();
        $printer->getWords();
        exit;
        */

        $jscode     = input('param.jscode/s');
        $userinfo   = input('param.userinfo/s');

        //微信 **用户授权
        $wechat     = new \app\core\provider\WeChat();
        $session    = $wechat->getSessionKey($jscode);
        if (!$session) {
            return jsonData(0, '请求失败,请重新发起');
        }

        //微信 **用户信息
        $session['userinfo']    = json_decode($userinfo, true);

        //数据库 userid
        $user                   = new \app\core\provider\User();
        $openid                 = $session['openid'];
        $session['userid']      = $user->initUserData($openid, $session);
        if ($session['userid'] == 0) {
            return jsonData(0, '用户初始化失败');
        }

        //设置用户SESSION
        $this->p_auth->setToken($session['userid'], $session);

        //返回
        return jsonData(1, 'ok', $session);
    }


    /***
     * 修改密码
     */
    function upPassword()
    {
        /**
         * 验证手机号 / 邮箱 合法性
         * 账号，密码， 验证合法性
         * 执行修改
         * 
         */
        return jsonData(1, 'ok', null);
    }


    /***
     * 修改手机号
     */
    function upPhone()
    {
        /**
         * 验证手机号 / 邮箱 合法性
         * 账号，密码， 验证合法性
         * 执行修改
         * 
         */
        return jsonData(1, 'ok', null);
    }


    /***
     * 管理收货地址
     */
    function address()
    {
        /**
         * 验证 合法性
         * 执行添加 / 更新
         * 
         */
        return jsonData(1, 'ok', null);
    }


    function send()
    {

        $phone   = input('get.phone/s');


        date_default_timezone_set('PRC'); //设置时区为东八区否则时间比北京时间早8 小时 
        $url = 'http://106.14.55.160:9000/HttpSmsMt';//接口地址 
        $mttime=date("YmdHis"); 
        $name = 'yxcs03';//开通的用户名 
        $password='d41d8cd98f00b204e9800998ecf8427e';//密码 
        $post_data['name'] = $name; 
        $post_data['pwd'] = md5($password.$mttime); 
        $post_data['content'] = '【伯格网络】验证码'. rand(10000, 99999).'。'; //$post_data['content'] = '123456'; 语音验证码内容 
        $post_data['phone'] = $phone;//'15084852913';//手机号码 $post_data['subid'] = ''; 
        $post_data['mttime']= $mttime; 
        $post_data['rpttype'] = '1'; 
        $o = ""; 
        
        //foreach ( $post_data as $k => $v ) { 
        //    $o.= "$k=" . urlencode( $v ). "&" ; 
        //} 
        
        //$post_data = substr($o,0,-1); 
        //$res = request_post($url, $post_data); 


        $host    = '106.14.55.160'; 
        $port    = 9000;
        $client  = new \app\core\provider\HttpClient($host, $port);

        $path = '/HttpSmsMt';
        if(!$client->post($path, $post_data)){
            print 0;

        }else{
            $res = $client->getContent();
            print $res;
        }

       

    }

}