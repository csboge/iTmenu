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


    
}