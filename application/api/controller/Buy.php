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
        $data['errcode'] = 1;
        var_dump(isset($data['errcode']));
        exit;
        $total     = input('post.total/f');


        $openid         = "opkjx0OfG53ZhOpEj-VWqpN_MxR0";       
        $body           = "充值余额";  
        $total_fee      = floatval($total * 100);  


        $wechat         = new \app\core\provider\WeChat();
        $result         = $wechat->payment($openid, $body, $total_fee);


        return jsonData(1, 'ok', $result);

    }

    //微信支付 回调
    public function notify()
    {
        # code...
    }



}