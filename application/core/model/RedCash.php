<?php
namespace app\core\model;

use think\Model;
use think\db;

/**
 * 红包方面 * 模型 * 操作方法
 *
 *
 *
 */
class RedCash extends Model 
{


    /**
     * 查询订单 - 是否已生成红包
     *
     * @param   string   $ordersn    订单号
     *
     * @return  result 
     *
     */
    public function getRedForOrderSN($ordersn)
    {
        $row = $this->where('order_sn', trim($ordersn))->find();

        return $row;
    }



    /**
     * 抢夺金额 - 自由算法
     *
     * @param   double   $surplus    剩余金额
     * @param   int      $nums       剩余数量
     * @param   int      $count      红包总数(暂时没有用到，后期更加平衡金额，可能用到)
     *
     * @return  double 
     *
     */
    public function getMoney($surplus, $nums, $count)
    {
        //最后一个， 全部余额
        if ($nums <= 1 || $surplus <= 0) return $surplus;

        //剩余金额 一半
        $max  = $surplus / 2;
        
        //额外 - 增加区间
        $offset   = array(0,1,0,1,0,0);
        if ($offset[rand(0,5)] == 1) {
            $ref  = rand(0, $max / 2);
            $max += $ref;
        }

        $min   = 0.01;
        $money = rand($min, $max);


        $min   = 0;
        $max   = 1;
        $ret   = $min + mt_rand() / mt_getrandmax() * ($max - $min);
        
        return round($money + $ret, 2);
    }


    /**
     * 查询订单 - 是否已生成红包
     *
     * @param   string   $bagid    红包id
     *
     * @return  result
     *
     */
    public function getRedList($bagid){

        $data = $this->where('id',$bagid)->field('menoy,surplus,num,get_num,words')->find()->toArray();

        return $data;
    }

    /**
     * 查询红包 - 时间是否过期
     *
     * @param   string   $bagid    红包id
     *
     * @return  result
     *
     */
    public function endTime($bagid){

        $res = $this->where('id',$bagid)->field('created')->find();

        return json_decode(json_encode($res), true);
    }

    /**
     * 查询红包 - 返回id
     *
     * @param   string   $order      订单id
     * @param   string   $shop       商户id
     *
     * @return  result
     *
     */
    public function isOrder($order,$shop){
        $map = [
            'order_sn' => $order,
            'shop_id' => $shop
        ];
        $res = $this->where($map)->field('id')->find();

        return $res;
    }

}