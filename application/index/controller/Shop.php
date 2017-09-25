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
        $where = input('param.');
        $map = [];
        if ($where) {
//            if (isset($where['starte']) && isset($where['ende'])) {
//                $starte = strtotime($where['starte']);
//                $ende = strtotime($where['ende']);
//                $map['created'] = array(['>',$starte],['<',$ende],'and');
//
//            }elseif (isset($where['starte'])){
//                $starte = strtotime($where['starte']);
//                $map['created'] = array('>', $starte);
//
//            }elseif (isset($where['ende'])){
//                $ende = strtotime($where['ende']);
//                $map['created'] = array('<', $ende);
//            }
            if (isset($where['username'])) {
                $username = $where['username'];
                $map['title'] = array('like', "%{$username}%");
            }
        }
        $map['hd_status'] = 1;
        $list = Db::name('shop')->order('id desc')->where($map)->paginate(10);
        $data = $list->all();
        foreach ($data as &$volue){
            $volue['shop_hours'] = json_decode($volue['shop_hours'],true);
        }
//        echo "<pre>";print_r($data);exit;
        $count = count_list('shop');
        // 获取分页显示
        $page = $list->render();
        // 模板变量赋值
        $this->assign('page', $page);
        $this->assign('count',$count);
        $this->assign('list',$data);
        return view();
    }



    //新增商店
    public function add(){
        if(input('post.')){
            $data = input('post.');
            $data['created'] = time();
            unset($data['img_url']);
            $data['logo'] = base64_img($data['logo']);
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
        $db['logo'] = ImgUrl($db['logo']);
        $this->assign('vo',$db);
        return view();
    }

    //修改操作页
    public function is_update(){
        $db = Db::name('shop');
        if(input('post.')){
            $data = input('post.');
            $data['updated'] = time();
            unset($data['img_url']);
            if($data['logo']){
                $data['logo'] = base64_img($data['logo']);
            }else{
                unset($data['logo']);
            }
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

    //管理页面
    public function admin(){
        $shop_id = input('param.');
        session('shop_id',$shop_id['id']);
        $map = [
            'hd_status' =>1
        ];
        $data = Db::name('admin')->where($map)->order('id asc')->select();
        $title = shop_name($shop_id['id']);
        session('shop_title',$title);
        $this->assign('title',$title);
        $this->assign('list',$data);
        return view();
    }

    //管理修改
    public function update_admin(){
        $id = input('id');
        $db = Db::name('admin');
        $list = $db->where(['id'=>$id])->find();
        $this->assign('vo',$list);
        return view();
    }

    //管理修改操作页
    public function is_admin_update(){
        $db = Db::name('admin');
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