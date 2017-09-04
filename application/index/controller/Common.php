<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/4
 * Time: 14:53
 */

namespace app\index\controller;

use think\Controller;
use think\Db;


class Common extends Controller
{
    //状态修改
    public function is_status(){
        $data = input('post.');
        $info = Db::name($data['name'])->where('id',$data['id'])->setField($data['status'],$data['volue']);
        if($info){
            return true;
        }else{
            return false;
        }
    }


    //状态修改
    public function is_delete(){
        $data = input('post.');
        $info = Db::name($data['name'])->where('id',$data['id'])->setField('hd_status',0);
        if($info){
            return true;
        }else{
            return false;
        }
    }

}