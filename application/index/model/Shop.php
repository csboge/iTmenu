<?php
namespace app\index\model;

use think\Model;


/**
 * 插件模型
 */
class Shop extends Model
{
    //查找商铺名称
    public function shop_name($id){
        $name = $this->where('id',$id)->find('name');
        return $name;
    }
}
