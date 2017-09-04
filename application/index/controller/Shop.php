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


class Shop extends Controller
{
    //商店列表
    public function index(){
        $data = Db::name('shop')->order('id ASC')->paginate(10);
        $this->assign('list',$data);
        return view();
    }

    //新增商店
    public function add(){
        return view();
    }


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

}