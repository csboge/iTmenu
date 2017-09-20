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
class Goods extends Model
{


    /**
     * 更改用户优惠券使用情况
     * @param   string   $shop         商户id
     *
     *
     * @return   string    立减金额
     *
     */
    public function getBowl($shop){
        $map = [
            'shop_id' => $shop,
            'bowl' => array('>','0'),
            'status' => 1,
            'hd_status' => 1,
            'sd_status' =>1
        ];

        $row = $this->where($map)->field('id,title,price,image,cat_id,bowl,attrs')->select();

        return json_decode(json_encode($row), true);
    }


}