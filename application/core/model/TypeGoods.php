<?php
namespace app\core\model;

use think\Model;


/**
 * 插件模型
 */
class TypeGoods extends Model
{
    //查找菜品类别名称
    public function typeId(){
        $name = $this->select();
        return json_decode(json_encode($name), true);
    }

    //查找菜品类别名称
    public function typeList($id){
        $name = $this->where('id',$id)->field('name')->find();

        return json_decode(json_encode($name['name']), true);
    }

}
