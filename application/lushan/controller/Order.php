<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/18
 * Time: 14:06
 */

namespace app\lushan\controller;


use think\Controller;

class Order extends Controller
{

    private $m_order;

    /***
     * 注入依赖
     *
     */
    public function __construct()
    {
        parent::__construct();

        //订单模型
        $this->m_order      = new \app\core\model\Orders();

    }


    /**
     * 待处理订单列表
     */
    public function stay(){
        $shop_id = session('shop_id');
        if(!$shop_id){
            $shop_id = 10;
        }

        $order = $this->m_order->orderStay($shop_id);
        $count = $this->m_order->orderStayCount($shop_id);

        // 获取分页显示
        $page = $order->render();

        // 模板变量赋值
        $this->assign('page', $page);
        $this->assign('list',$order);
        $this->assign('count',$count);

        return view();
    }

    /**
     * 结束订单操作
     */
    public function endOrder(){
        $order_sn = $this->request->param('order_sn');
        $shop_price = $this->request->param('shop_price');

        //结束订单
        $order = $this->m_order->endOrder($order_sn,$shop_price);

        return $order;
    }


    /**
     * 已处理订单查询
     */
    public function finish(){
        $shop_id = session('shop_id');
        if(!$shop_id){
            $shop_id = 10;
        }

        $order = $this->m_order->orderFinish($shop_id);
        $count = $this->m_order->orderFinishCount($shop_id);

        // 获取分页显示
        $page = $order->render();

        // 模板变量赋值
        $this->assign('page', $page);
        $this->assign('list',$order);
        $this->assign('count',$count);

        return view();
    }

}