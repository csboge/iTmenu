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


    /**
     * 提交订单
     *
     *
     */
    public function submitOrder()
    {

        //$jscode     = input('post.jscode/s');

        $wechat     = new \app\core\provider\WeChat();
        
        $result     = $wechat->payment();


        return jsonData(1, 'ok', $result);

    }

    //微信支付 回调
    public function notify()
    {
        # code...
    }



}