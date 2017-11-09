<?php
namespace app\index\model;

use think\Model;


/**
 * 插件模型
 */
class TypeGoods extends Model
{
    //查找菜品类别名称
    public function typeId($shop_id){
        $map = [
            'shop_id' => $shop_id
        ];
        $name = $this->where($map)->select();

        return json_decode(json_encode($name), true);
    }

}
