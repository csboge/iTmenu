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

        $printer    = new \app\core\provider\BotPrinter();
        $sn         = '217502439';

        $orderInfo = '<CB>伯格 - 电子菜谱</CB><BR>';
		$orderInfo .= '名称　　　　　 单价  数量 金额<BR>';
		$orderInfo .= '--------------------------------<BR>';
		$orderInfo .= '饭　　　　　 　10.0   10  10.0<BR>';
		$orderInfo .= '伯格  　　　　 10.0   10  10.0<BR>';
		$orderInfo .= '蛋炒饭　　　　 10.0   100 100.0<BR>';
		$orderInfo .= '伯格网络　　　 100.0  100 100.0<BR>';
		$orderInfo .= '西红柿炒饭　　 1000.0 1   100.0<BR>';
		$orderInfo .= '西红柿蛋炒饭　 100.0  100 100.0<BR>';
		$orderInfo .= '西红柿鸡蛋炒饭 15.0   1   15.0<BR>';
		$orderInfo .= '备注：加辣，伯格网络专用辣椒<BR>';
		$orderInfo .= '--------------------------------<BR>';
		$orderInfo .= '合计：100.0元<BR>';
		$orderInfo .= '送货地点：长沙市万达广场C2-3508<BR>';
		$orderInfo .= '联系电话：0731-85056818<BR>';
		$orderInfo .= '订餐时间：'.date('Y-m-d H:i:s', time()).'<BR>';
		$orderInfo .= '<QR>http://www.csboge.com</QR>';//把二维码字符串用标签套上即可自动生成二维码

        $re = $printer->wp_print($sn, $orderInfo, 1);
        echo $re;
        exit;

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