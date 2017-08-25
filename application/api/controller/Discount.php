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


        //一条红包信息               红包总数      已抢数量
        $baginfo    = ['红包id'=>1, 'count'=>10, 'speed'=>5];











        return jsonData(1, 'ok', $baginfo);
    }


    /***
     * 红包 - 抢夺
     */
    function robbed()
    {

        //红包id
        $bagid      = input('param.bagid/d');


        //语音口令(二进制)
        $bagid      = input('param.audio');


        //用户信息
        $session    = $this->p_auth->session();












        //抢到红包金额     已抢数量
        return jsonData(1, 'ok', ['money'=>10, 'speed'=>6]);
    }



    /***
     * 红包 - 生成朋友圈 - 转发图片
     */
    function robimg()
    {

        //红包id
        $bagid      = input('param.bagid/d');


        //用户信息
        $session    = $this->p_auth->session();








     
        return jsonData(1, 'ok', ['imgurl'=>'']);
    }   


    /***
     * 红包 - 详细
     */
    function robInfo()
    {

        //红包id
        $bagid      = input('param.bagid/d');

        //用户信息
        $session    = $this->p_auth->session();

        //一条红包信息
        $baginfo    = ['bagid'=>1, 'count'=>10, 'speed'=>6, 'words'=>'语音口令', 'speed'=>5, 
            'user_list'=>[
                [
                    'nickname'  => '',
                    'avatar'    => '',
                    'sex'       => 0,
                    'money'     => '',      //抢到金额
                    'time'      => time(),
                    'audio'     => ''       //音频文件
                ]
            ],
            'banners' => [
                [
                    'url' => ''
                ],
                [
                    'url' => ''
                ]
            ]
        ];



        return jsonData(1, 'ok', $baginfo);
    }



}