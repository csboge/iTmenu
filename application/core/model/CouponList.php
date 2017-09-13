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
class CouponList extends Model
{


    /**
     * 更改用户优惠券使用情况
     * @param   string   $userid         用户id
     *
     * @param   string   $coupon_id    优惠券id
     *
     * @return   string    立减金额
     *
     */
    public function CouponStatus($userid,$coupon_id){
        $map = [
            'user_id' => $userid,
            'coupon_id' => $coupon_id
        ];
        $row = $this->where($map)->setField('u_status',1);

        return $row;
    }


}