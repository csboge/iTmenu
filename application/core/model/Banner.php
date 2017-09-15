<?php
namespace app\core\model;

use think\Model;
use think\db;

/**
 * 抢红包 * 模型 * 操作方法
 *
 *
 *
 */
class Banner extends Model
{


    /**
     * 查询抢红包列表
     *
     * @param   string   $shop     商户id
     *
     * @return   string
     *
     */
    public function isBanner($shop){

        $map = [
            'cat_id' => 1,
            'shop_id' => $shop,
            'hd_status' => 1,
            'status' => 1
        ];

        $row = $this->where($map)->field('image')->select();
        $data = [];
        foreach ($row as $volue){
            $data[] = $volue->toArray();
        }

        return $data;
    }

}