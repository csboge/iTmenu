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
class RedCashLog extends Model
{


    /**
     * 查询抢红包列表
     *
     * @param   string   $shop     商户id
     * @param   string   $bagid    红包id
     *
     * @return   string
     *
     */
    public function isRedList($shop,$bagid){

        $map = [
            'red_cash_id' => $bagid,
            'shop_id' => $shop
        ];

        $row = $this->where($map)->order('id desc')->field('user_id,audio,menoy,created')->select();
        $data = [];
        foreach ($row as $volue){
            $data[] = $volue->toArray();
        }

        return $data;
    }

}