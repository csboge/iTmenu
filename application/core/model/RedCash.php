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
        if ($nums <= 1) return $surplus;

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

}