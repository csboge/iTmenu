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
     * 查询付款人数
     *
     * @param   string   $tistics     商户收入id
     *
     * @return  string    总金额
     *
     */
    public function ordersTistics($tistics)
    {
        $map = [
            'tistics_id'   => $tistics,
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

    /**
     * 更新入账记录
     *
     * @param   string   $orders        订单号
     * @param   string   $userid        用户id
     * @param   string   $tistics       入账id
     *
     * @return  array    用户数据
     *
     */
    public function upTistics($orders,$userid,$tistics){
        $map = [
            'order_sn'      => $orders,
            'user_id'       => $userid
        ];

        $row = $this->where($map)->setField('tistics_id', $tistics);

        return $row;
    }

    /**
     * 查询商户入账下的订单
     *
     * @param   string   $tistics        订单号
     * @param   string   $shopid         商户id
     *
     * @return  array    订单数据
     *
     */
    public function liseOrder($tistics,$shopid){
        $map = [
            'tistics_id'    => $tistics,
            'shop_id'       => $shopid
        ];
        $row = $this->where($map)->field('order_sn,shop_id,user_id,shop_price,mode_money,pay_time')->select();

        return json_decode(json_encode($row), true);
    }


    /**
     * 查询商家完成支付的总订单
     * @param $shop_id
     * @return int|string
     */
    public function orderStatistics($shop_id){
        $map = [
            'shop_id'       => $shop_id,
            'status'        => 1,
            'is_complete'   => 1
        ];

        $mas = [
            'shop_id'       => $shop_id,
            'status'        => 1
        ];
        $data['count']          = $this->where($mas)->count();
        $data['total_price']    = $this->where($mas)->sum('total_price');
        $data['shop_price']     = $this->where($map)->sum('shop_price');

        return $data;
    }

    /**
     * 待处理订单查询
     * @param $shop_id
     */
    public function orderStay($shop_id){
        $map = [
            'shop_id'       => $shop_id,
            'is_complete'   => 0
        ];

        $data = $this->where($map)->order('created desc')->paginate(10);

        return $data;
    }


    /**
     * 待处理订单查询的总条数
     * @param $shop_id
     */
    public function orderStayCount($shop_id){
        $map = [
            'shop_id'       => $shop_id,
            'is_complete'   => 0
        ];

        $data = $this->where($map)->count();

        return $data;
    }


    /**
     * 结束订单
     * @param $order_sn
     * @param $shop_price
     * @return $this
     */
    public function endOrder($order_sn,$shop_price){
        $map = [
            'order_sn'      => $order_sn
        ];

        $rew = $this->where($map)->update(['shop_price'=>$shop_price,'is_complete'=>1]);

        return $rew;
    }


    /**
     * 已处理订单查询
     * @param $shop_id
     * @return \think\paginator\Collection
     */
    public function orderFinish($shop_id){
        $map = [
            'shop_id'       => $shop_id,
            'is_complete'   => 1
        ];

        $data = $this->where($map)->order('created desc')->paginate(10);

        return $data;
    }


    /**
     * 已处理订单查询的总条数
     * @param $shop_id
     */
    public function orderFinishCount($shop_id){
        $map = [
            'shop_id'       => $shop_id,
            'is_complete'   => 1
        ];

        $data = $this->where($map)->count();

        return $data;
    }

}