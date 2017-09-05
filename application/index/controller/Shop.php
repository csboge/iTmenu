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
        $data = Db::name('shop')->order('id ASC')->where('hd_status',1)->paginate(10);
        $count = count_list('shop');
        $this->assign('count',$count);
        $this->assign('list',$data);
        return view();
    }

    //新增商店
    public function add(){
        if(input('post.')){
            $data = input('post.');
            $data['created'] = time();
            $res = Db::name('shop')->insert($data);
            if($res){
                return true;
            }else{
                return false;
            }
        }else{
            $list = Db::name('shop')->where('id>0')->select();
            $this->assign('list',$list);
        }
        return view();
    }


    //修改商店显示页
    public function update(){
        $data = input('id');
        if(empty($data))return false;
        $db = Db::name('shop')->where('id',$data)->find();
        $this->assign('vo',$db);
        return view();
    }

    //修改操作页
    public function is_update(){
        $db = Db::name('shop');
        if(input('post.')){
            $data = input('post.');
            $data['updated'] = time();
            $res = $db->where('id',$data['id'])->update($data);
            if($res){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

}