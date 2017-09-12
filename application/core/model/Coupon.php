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
class Coupon extends Model
{


    /**
     * 查询商户立减金额
     *
     * @param   string   $coupon_id    优惠券id
     *
     * @return   string    立减金额
     *
     */
    public function isCoupon($coupon_id){

        $row = $this->where('id',$coupon_id)->field('dis_price')->find()->toArray();

        return $row;
    }

}