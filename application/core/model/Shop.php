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
class Shop extends Model
{


    /**
     * 查询商户立减金额
     *
     * @param   string   $shopid    商户id
     *
     * @return   string    立减金额
     *
     */
    public function isShopMoney($shopid){
        $row = $this->where('id',$shopid)->field('is_first')->find()->toArray();

        return $row['is_first'];
    }

}