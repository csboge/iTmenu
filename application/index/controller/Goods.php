<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/4
 * Time: 14:53
 */

namespace app\index\controller;

use app\index\model\TypeGoods;
use think\Controller;
use think\Db;
use app\index\model\Shop;


class Goods extends Controller
{
    //菜品分类列表
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
                $map['name'] = array('like', "%{$username}%");
            }
        }
        $map['hd_status'] = 1;
        $map['shop_id'] = session('shop_id');
//        echo "<pre>";print_r($map);exit;
        $data = Db::name('category')->order('rank asc')->order('id desc')->where($map)->paginate(10);
        $count = count_list('category','shop_id',session('shop_id'));
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

    //新增菜品分类
    public function add(){
        if(input('post.')){
            $data = input('post.');
            $data['created'] = time();
            $data['shop_id'] = session('shop_id');
            $res = Db::name('category')->insert($data);
            if($res){
                return true;
            }else{
                return false;
            }
        }else{
            $info = Db::name('category')->where(['parent_id'=>0,'hd_status'=>1,'status'=>1,'shop_id'=>session('shop_id')])->select();
            $this->assign('info',$info);
        }
        return view();
    }


    //修改菜品分类显示页
    public function update(){
        $data = input('param.');
        if(empty($data))return false;
        $db = Db::name('category')->where('id',$data['id'])->find();
        $info = Db::name('category')->where(['parent_id'=>0,'hd_status'=>1,'status'=>1,'shop_id'=>session('shop_id')])->select();
        $this->assign('info',$info);
        $this->assign('vo',$db);
        return view();
    }

    //修改操作页
    public function is_update(){
        if(input('post.')){
            $data = input('post.');
            $data['updated'] = time();
            $db = Db::name('category');
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


    //套餐列表
    public function package_index(){
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
                $map['name'] = array('like', "%{$username}%");
            }
        }

        $map['shop_id'] = session('shop_id');
        $map['hd_status'] = 1;

        $data = Db::name('package')->where($map)->order('rank asc')->paginate(10);
        $count = count_list('package','shop_id',session('shop_id'));
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

    //新增套餐
    public function package_add(){
        if(input('post.')){
            $data = input('post.');
            $data['created'] = time();
            $data['shop_id'] = session('shop_id');
            $res = Db::name('package')->insert($data);
            if($res){
                return true;
            }else{
                return false;
            }
        }else{
            $info = Db::name('package')->order('rank asc')
                ->where(['hd_status'=>1,'sd_status'=>1,'shop_id'=>session('shop_id')])
                ->select();
            $this->assign('info',$info);
        }
        return view();
    }


    //修改套餐显示页
    public function package_update(){
        $data = input('param.');
        if(empty($data))return false;
        $db = Db::name('package')->where('id',$data['id'])->find();
        $this->assign('vo',$db);
        return view();
    }

    //修改操套餐作页
    public function package_is_update(){
        if(input('post.')){
            $data = input('post.');
            $data['updated'] = time();
            $db = Db::name('package');
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


    //菜品列表
    public function goods_index(){
        $map = [];
        if (input('param.')) {
            $where = input('param.');
//            if (isset($where['start']) && isset($where['end'])) {
//                $start = strtotime($where['start']);
//                $end = strtotime($where['end']);
//                $map['created'] = array(['>',$start],['<',$end],'and');
//
//            }elseif (isset($where['start'])){
//                $start = strtotime($where['start']);
//                $map['created'] = array('>', $start);
//
//            }elseif (isset($where['end'])){
//                $end = strtotime($where['end']);
//                $map['created'] = array('<', $end);
//            }
            if (isset($where['username'])) {
                $username = $where['username'];
                $map['title'] = array('like', "%{$username}%");
            }
        }
        $map['hd_status'] = 1;
        $map['shop_id'] = session('shop_id');
        $data = Db::name('goods')->where($map)->order('rank asc')->order('rec desc')->order('id desc')->paginate(10);
//        echo '<pre>';print_r($aa);exit;

        $count = count_list('goods','shop_id',session('shop_id'));
        // 获取分页显示
        $page = $data->render();
        // 模板变量赋值
        $this->assign('page', $page);
        $title = session('shop_title');
        $this->assign('title',$title);
        $this->assign('count',$count);
        $this->assign('list',$data);
        return view();
    }

    //新增菜品
    public function goods_add(){
        if(input('post.')){
            $data = input('post.');
            $data['image'] = base64_img($data['image']);
            $data['created'] = time();
            $data['shop_id'] = session('shop_id');
            foreach ($data['titles'] as $key=>$volue){
                $data['attrs'][$key]['titles'] = $volue?$volue:'';
                $data['attrs'][$key]['prices'] = $data['prices'][$key]?$data['prices'][$key]:'';
            }
            $data['attrs'] = json_encode($data['attrs'],true);
            unset($data['titles']);
            unset($data['prices']);
            unset($data['img_url']);
            $res = Db::name('goods')->insert($data);
            if($res){
                return true;
            }else{
                return false;
            }
        }else{
            $info = Db::name('category')->where(['hd_status'=>1,'status'=>1,'shop_id'=>session('shop_id')])->select();
            $list = Db::name('package')->where(['hd_status'=>1,'sd_status'=>1,'shop_id'=>session('shop_id')])->select();
            $typeId = new TypeGoods();
            $type = $typeId->typeId(session('shop_id'));
            $this->assign('type',$type);
            $this->assign('info',$info);
            $this->assign('list',$list);
        }
        return view();
    }


    //修改菜品显示页
    public function goods_update(){
        $data = input('param.');
        if(empty($data))return false;
        $db = Db::name('goods')->where('id',$data['id'])->find();
        $db['image'] = ImgUrl($db['image']);
        $info = Db::name('category')->where(['hd_status'=>1,'status'=>1,'shop_id'=>session('shop_id')])->select();
        $list = Db::name('package')->where(['hd_status'=>1,'sd_status'=>1,'shop_id'=>session('shop_id')])->select();
        $typeId = new TypeGoods();
        $type = $typeId->typeId(session('shop_id'));
        $this->assign('type',$type);
        $this->assign('info',$info);
        $this->assign('list',$list);
        $this->assign('vo',$db);
        return view();
    }

    //修改操作页
    public function as_update(){
        if(input('post.')){
            $data = input('post.');
            foreach ($data['titles'] as $key=>$volue){
                $data['attrs'][$key]['titles'] = $volue;
                $data['attrs'][$key]['prices'] = $data['prices'][$key];
            }
            $data['attrs'] = array_merge($data['attrs']);
            $data['attrs'] = json_encode($data['attrs'],true);
            $data['updated'] = time();;
            unset($data['titles']);
            unset($data['prices']);
            unset($data['img_url']);
            if($data['image']){
                $data['image'] = base64_img($data['image']);
            }else{
                unset($data['image']);
            }
            $db = Db::name('goods');
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