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
     * @return  result 
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
}