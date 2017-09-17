<?php
namespace app\index\model;

use think\Model;


/**
 * 插件模型
 */
class User extends Model
{
    //查找用户信息
    public function userList($id){
        $db = $this->where('id',$id)->field('nickname,sex,openid,mobile,city,province')->find()->toArray();
        return $db;
    }

    //修改用户为管理员
    public function userAdmin($id){
        $db = $this->where('id',$id)->setField('is_admin',1);
        return $db;
    }
}
