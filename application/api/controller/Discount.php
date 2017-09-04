<?php
namespace app\api\controller;

use think\Request;

class Discount
{
    private     $p_auth;

    /***
     * 公共 - 注入依赖
     *
     */
    public function __construct(
        Request                         $request,
        \app\core\provider\Auth         $p_auth,
        \app\core\model\RedCash         $m_red
    )
    {
        //验证授权合法
        $p_auth->check($request, [
            'public' => [],
            'private'=> []
        ]);

        //授权服务
        $this->p_auth   = $p_auth;

        //红包模型
        $this->m_red    = $m_red;
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

        if(!$ordersn) { 
            return jsonData(-1, '订单号 - 不能为空。');
        }

        if(!$words) { 
            return jsonData(-2, '语音口令 - 不能为空。');
        }

        //用户信息
        $session    = $this->p_auth->session();

        //获得商店id
        $shopid     = $this->p_auth->getShopId();


        //查询订单 - 是否已生成红包
        $red_info   = $this->m_red->getRedForOrderSN($ordersn);
        if($order_info) {
            return jsonData(-3, '该订单 - 已生成红包。');
        }

        //查询订单
        $order_info            = $this->m_order->getOrderForSN($ordersn);
        if(!$order_info) { 
            return jsonData(-4, '该订单 - 已不存在。');
        }


        $num  = rand($order_info['user_count'], $order_info['user_count'] * 2);
        $data = [
            'order_sn'  => $ordersn,
            'words'     => $words,
            'shop_id'   => $order_info['shop_id'],
            'menoy'     => $order_info['mode_money'],
            'user_id'   => $session['userid'],
            'num'       => $num,
            'created'   => time(),
            'updated'   => time(),
        ];
        $result = $this->m_red->data($data)->save();

        if (!$result) {
            return jsonData(0, '抱歉 - 生成失败。');
        }


        //一条红包信息                              红包总数      已抢数量
        $baginfo    = ['bagid'=>$this->m_red->id, 'count'=>$num, 'speed'=>0];

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
        $baginfo    = ['bagid'=>1, 'count'=>10, 'speed'=>6, 'words'=>'语音口令', 'total_money'=>5, 
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