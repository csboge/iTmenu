<?php
namespace app\api\controller;

use think\Request;

class Menu
{
    private     $p_auth;

    /***
     * 公共 - 注入依赖
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
     * 红包 - 生成
     */
    function create()
    {

        //订单号
        $ordersn    = input('param.ordersn/s');

        //语音口令
        $words      = input('param.words/s');

        //用户信息
        $session    = $this->p_auth->session();


        //一条红包信息
        $baginfo    = ['红包id'=>1, '红包数量'=>10, '已抢数量'=>5];



        return jsonData(1, 'ok', $baginfo);
    }


    /***
     * 红包 - 抢夺
     */
    function robbed()
    {

        //红包id
        $bagid      = input('param.bagid/d');

        //用户信息
        $session    = $this->p_auth->session();






        return jsonData(1, 'ok', ['抢红包金额'=>10, '已抢数量'=>6]);
    }


}