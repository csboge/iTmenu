<?php
namespace app\core\model;

use think\Model;
use think\db;

/**
 * 订单方面 * 模型 * 操作方法
 *
 *
 *
 */
class Orders extends Model 
{


    /**
     * 查询订单（订单号）
     *
     * @param   string   $ordersn    订单号
     *
     * @return   array    查到的订单
     *
     */
    public function getOrderForSN($ordersn)
    {
        $row = $this->where('order_sn', trim($ordersn))->find();

        return $row;
    }



    /**
     * 是否是新顾客
     *
     * @param   int     $shopid    商户id
     * @param   int     $userid    顾客id
     * @param   array   $info      数组
     *
     * @return  array   最后一次消费
     */
    public function isFirstOrder($shopid, $userid)
    {
        
        $row = $this->where('order_sn', trim($ordersn))->find();

        return $row;
    }

    /**
     * 是否是新顾客
     *
     * @param   int     $shopid    商户id
     * @param   int     $userid    顾客id
     *
     * @return  array   最后一次消费
     */
    public function isFirstCons($shopid,$userid){
        $map = [
            'shop_id'   => $shopid,
            'user_id'   => $userid,
            'status'    => 1
        ];
        $row = $this->where($map)->count();

        return $row;
    }


    /**
     * 错误处理
     *
     * @param   int     $order_sn     订单id
     *
     * @return  int     处理条数
     */
    public function error_log($order_sn){

        $data = $this->where('order_sn',$order_sn)->setDec('status',1);

        return $data;
    }

    /**
     * 查询总额
     *
     * @param   string   $shop_id     商户id
     *
     * @return  string    总金额
     *
     */
    public function ordersMoney($shop_id)
    {
        $map = [
            'shop_id'   => $shop_id,
            'status'    => 1
        ];
        $money = $this->where($map)->sum('shop_price');

        return $money;
    }

    /**
     * 查询付款人数
     *
     * @param   string   $shop_id     商户id
     *
     * @return  string    总金额
     *
     */
    public function ordersPeople($shop_id)
    {
        $map = [
            'shop_id'   => $shop_id,
            'status'    => 1
        ];
        $people = $this->where($map)->count();

        return $people;
    }

    /**
     * 查询老用户用户人数
     *
     * @param   string   $shop_id     商户id
     *
     * @return  array    用户数据
     *
     */
    public function isOld($shop_id){
        $map = [
            'shop_id'   => $shop_id,
            'status'    => 1
        ];
        $first = $this
            ->where($map)
            ->group('user_id')
            ->having('count(user_id)>1')
            ->select();

        return json_decode(json_encode($first), true);
    }

    /**
     * 查询老用户用户人数
     *
     * @param   string   $shop_id     商户id
     *
     * @return  array    用户数据
     *
     */
    public function isFirst($shop_id){
        $map = [
            'shop_id'   => $shop_id,
            'status'    => 1
        ];
        $first = $this
            ->where($map)
            ->group('user_id')
            ->having('count(user_id)=1')
            ->select();

        return json_decode(json_encode($first), true);
    }

    /**
     * 查询时间段的订单
     *
     * @param   string   $shop        商户id
     * @param   string   $start       开始时间
     * @param   string   $end         结束时间
     *
     * @return  array    用户数据
     *
     */
    public function timeOrders($shop,$start,$end){
        $map = [
            'shop_id'   => $shop,
            'status'    => 1,
            'pay_time'  => array(['>',$start],['<',$end],'and'),
        ];
        $data = $this->where($map)->select();

        return json_decode(json_encode($data), true);
    }

}