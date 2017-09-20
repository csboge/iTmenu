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

    /***
     * 获取 -- 用户优惠券
     *
     * @param   int      $userid       用户id
     * @param   int      $shopid       商户id
     *
     * @return   string    立减金额
     */
    public function getCoupon($userid,$shopid){
        $map = [
            'user_id' => $userid,
            'shop_id' => $shopid,
            'status' => 1,
            'u_status' => 0
        ];
        $data = $this->where($map)->field('coupon_id,status,get_time,use_time,u_status')->select();

        return json_decode(json_encode($data), true);
    }

}