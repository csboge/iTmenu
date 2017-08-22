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
        /**
         * 验证手机号 / 邮箱 合法性
         * 账号，密码， 验证合法性
         * 执行登录
         * 
         */

        
        $session    = ['userid'=>1072, 'examid'=>2];
        $data       = $this->p_auth->setToken($session);

        return jsonData(1, 'ok', $data);
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

}