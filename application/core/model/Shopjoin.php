<?php
namespace app\core\model;

use think\Model;
use think\db;

/**
 * 商户方面 * 模型 * 操作方法
 *
 *
 *
 */
class Shopjoin extends Model 
{


    /**
     * 商户入驻（）
     *
     * @param   string   $data    商家入驻填写的信息
     *
     * @return  result 
     * 
     *
     */
    //插入商家入驻信息
    public function getShopJoin($data)
    {
        $str=Db::name('shop')->insert($data);

        return $str;
    }


   
     
    /**
     * 查询商户以入驻的信息（）
     *
     * @param   string   $shopunid    商户唯一标示
     * 
     *
     * @return  result  
     * 
     *
     */
    //查询商户以入驻的信息（）
      public function  getShopJoins($data)
      {
            $row = Db::table('bg_shop')->select();

            return $row;
      }


    


    //  /**
    //  * 查询商家入驻信息（unionid）
    //  *
    //  * @param   string   $unionid    用户唯一标识
    //  *
    //  * @return  result 
    //  *
    //  */
    // public function getUserForUnionid($unionid)
    // {
    //     $row = $this->where('unionid', trim($unionid))->find();

    //     return $row;
    // }


    
}