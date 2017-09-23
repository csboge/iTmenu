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
use app\index\model\Shop;


class Table extends Controller
{
    //桌子分类列表
    public function index(){
        $where = input('param.');
        $map = [];
        if ($where) {
            if (isset($where['starte']) && isset($where['ende'])) {
                $starte = strtotime($where['starte']);
                $ende = strtotime($where['ende']);
                $map['created'] = array(['>',$starte],['<',$ende],'and');

            }elseif (isset($where['starte'])){
                $starte = strtotime($where['starte']);
                $map['created'] = array('>', $starte);

            }elseif (isset($where['ende'])){
                $ende = strtotime($where['ende']);
                $map['created'] = array('<', $ende);
            }
            if (isset($where['username'])) {
                $username = $where['username'];
                $map['name'] = array('like', "%{$username}%");
            }
        }
        $map['shop_id'] = session('shop_id');
        $map['hd_status'] = 1;

        $data = Db::name('table_list')->order('id desc')->where($map)->paginate(10);
        $count = count_list('table_list','shop_id',session('shop_id'));
        $title = session('shop_title');
        // 获取分页显示
        $page = $data->render();
        // 模板变量赋值
        $this->assign('page', $page);
        $this->assign('title',$title);
        $this->assign('count',$count);
        $this->assign('list',$data);
        return view();
    }

    //新增桌子分类
    public function add(){
        if(input('post.')){
            $data = input('post.');
            if(session('shop_id')){
                $data['shop_id'] = session('shop_id');
            }else{
                return true;
            }
            $data['created'] = time();
            $res = Db::name('table_list')->insert($data);
            if($res){
                return true;
            }else{
                return false;
            }
        }
        return view();
    }


    //修改桌子分类显示页
    public function update(){
        $data = input('param.');
        if(empty($data))return false;
        $db = Db::name('table_list')->where('id',$data['id'])->find();
        $this->assign('vo',$db);
        return view();
    }

    //修改操作页
    public function is_update(){
        if(input('post.')){
            $data = input('post.');
            $data['updated'] = time();
            $db = Db::name('table_list');
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


    //桌子列表
    public function table_index(){
        $where = input('param.');
        $map = [];
        if ($where) {
            if (isset($where['starte']) && isset($where['ende'])) {
                $starte = strtotime($where['starte']);
                $ende = strtotime($where['ende']);
                $map['created'] = array(['>',$starte],['<',$ende],'and');

            }elseif (isset($where['starte'])){
                $starte = strtotime($where['starte']);
                $map['created'] = array('>', $starte);

            }elseif (isset($where['ende'])){
                $ende = strtotime($where['ende']);
                $map['created'] = array('<', $ende);
            }
            if (isset($where['username'])) {
                $username = $where['username'];
                $map['name'] = array('like', "%{$username}%");
            }
        }
        $map['shop_id'] = session('shop_id');
        $map['hd_status'] = 1;
        $data = Db::name('table')->order('id desc')->where($map)->paginate(10);
        $count = count_list('table','shop_id',session('shop_id'));
        $title = session('shop_title');
        // 获取分页显示
        $page = $data->render();
        // 模板变量赋值
        $this->assign('page', $page);
        $this->assign('title',$title);
        $this->assign('count',$count);
        $this->assign('list',$data);
        return view();
    }

    //新增桌子
    public function table_add(){
        if(input('post.')){
            $data = input('post.');
            $data['created'] = time();
            if(session('shop_id')){
                $data['shop_id'] = session('shop_id');
            }else{
                return true;
            }
            unset($data['img_url']);
            $data['image'] = base64_img($data['image']);
            $res = Db::name('table')->insert($data);
            if($res){
                return true;
            }else{
                return false;
            }
        }else{
            $map = [
                'hd_status' => 1,
                'status' => 1,
                'shop_id' => session('shop_id')
            ];
            $info = Db::name('table_list')->where($map)->select();
            $this->assign('info',$info);
        }
        return view();
    }


    //修改桌子显示页
    public function table_update(){
        $data = input('param.');
        if(empty($data))return false;
        $db = Db::name('table')->where('id',$data['id'])->find();
        $db['image'] = ImgUrl($db['image']);
        $map = [
            'hd_status' => 1,
            'status' => 1,
            'shop_id' => session('shop_id')
        ];
        $info = Db::name('table_list')->where($map)->select();
        $list = Db::name('shop')->where(['hd_status'=>1,'status'=>1])->select();
        $this->assign('info',$info);
        $this->assign('list',$list);
        $this->assign('vo',$db);
        return view();
    }

    //修改操作页
    public function as_update(){
        if(input('post.')){
            $data = input('post.');
            $data['updated'] = time();
            unset($data['img_url']);
            if($data['image']){
                $data['image'] = base64_img($data['image']);
            }else{
                unset($data['image']);
            }
            $db = Db::name('table');
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

    //管理
    public function admin(){
        $title = session('shop_title');
        $shop = session('shop_id');
        $this->assign('shop',$shop);
        $this->assign('title',$title);
        return view();
    }

}