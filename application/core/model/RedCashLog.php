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

    /**
     * 查询用户是否已抢过红包
     *
     * @param   string   $user     用户id
     * @param   string   $bagid    红包id
     *
     * @return   string
     *
     */
    public function isUserRed($user,$bagid){
        $map = [
            'user_id' => $user,
            'red_cash_id' => $bagid
        ];
        $row = $this->where($map)->count();
        return $row;
    }

}