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
class Tistics extends Model
{
    /**
     * 查询是否有当天次商户的数据
     *
     * @param   string   $shopid    商户id
     *
     * @return   int       id
     *
     */
    public function isTistics($shopid){
        $map = [
            'shop_id'       => $shopid,
            'statistics'    => date('Y-m-d',time())
        ];
        $row = $this->where($map)->find();

        return json_decode(json_encode($row), true);
    }


    /**
     * 新增商户统计表
     *
     * @param   string   $shopid    商户id
     * @param   string   $money     订单金额
     *
     * @return   int       id
     *
     */
    public function insertTistics($shopid,$money){
        $map = [
            'shop_id'       => $shopid,
            'money'         => $money,
            'statistics'    => date('Y-m-d',time()),
            'created'       => time()
        ];
        $row = $this->insertGetId($map);
        return $row;
    }

    /**
     * 更新商户统计表
     *
     * @param   string   $id        统计数据id
     * @param   string   $money     订单金额
     *
     * @return   int       id
     *
     */
    public function updateTistics($id,$money){
        $row = $this->where('id',$id)->setInc('money',$money);
        if($row){
            return $id;
        }else{
            return $row;
        }
    }

    /**
     * 查询商户下的所有数据
     *
     * @param   string   $shopid      商户id
     *
     * @return   int       id
     *
     */
    public function listTistics($shopid){
        $row = $this->where('shop_id',$shopid)->field('id,money,statistics')->select();

        return json_decode(json_encode($row), true);
    }

    /**
     * 查询商户下当天的收益
     *
     * @param   string   $shopid      商户id
     *
     * @return   int       id
     *
     */
    public function toeles($shopid){
        $map = [
          'shop_id' => $shopid,
          'statistics' => date('Y-m-d 00:00:00',time())
        ];
        $row = $this->where($map)->field('id,money,statistics')->find();
        return json_decode(json_encode($row), true);
    }

}