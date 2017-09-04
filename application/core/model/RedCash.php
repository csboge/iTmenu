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


}