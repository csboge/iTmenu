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
            $data['logo'] = base64_img($data['logo']);
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

    //轮播图列表
    public function banner_list(){
        $id = input('id');
        if(empty($id))return false;
        $map = [
            'shop_id' => $id,
            'hd_status' => 1
        ];
        $data = Db::name('banner_list')->where($map)->select();
        $title = Db::name('shop')->where(['id'=>$id])->field('title')->find();
        $count = count_list('banner_list');
        $this->assign('count',$count);
        $this->assign('list',$data);
        $this->assign('title',$title['title']);
        return view();
    }

    //新增轮播图分类
    public function list_add(){
        if(input('post.')){
            $data = input('post.');
            $data['created'] = time();
            $res = Db::name('banner_list')->insert($data);
            if($res){
                return true;
            }else{
                return false;
            }
        }else{
            $list = Db::name('shop')->where(['hd_status'=>1,'status'=>1])->select();
            $this->assign('list',$list);
        }
        return view();
    }

    //修改轮播图显示页
    public function list_update(){
        $data = input('param.');
        if(empty($data))return false;
        $db = Db::name('banner_list')->where('id',$data['id'])->find();
        $list = Db::name('shop')->where(['hd_status'=>1,'status'=>1])->select();
        $this->assign('list',$list);
        $this->assign('vo',$db);
        return view();
    }

    //轮播图分类修改操作
    public function list_is_update(){
        $db = Db::name('banner_list');
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



    //轮播图列表
    public function banner(){
        $data = input('param.');
        if(empty($data))return false;
        $map = [
            'shop_id' => $data['shop_id'],
            'cat_id' => $data['id'],
            'hd_status' => 1
        ];
        $res = Db::name('banner')->where($map)->select();
        $title = Db::name('banner_list')->where(['id'=>$data['id']])->field('title')->find();
        $count = count_list('banner');
        $this->assign('count',$count);
        $this->assign('list',$res);
        $this->assign('title',$title['title']);
        $this->assign('shop_id',$data['shop_id']);
        return view();
    }


    //轮播图列表
    public function banner_add(){
        $data = input('param.');
        if(empty($data))return false;
        print_r($data);exit;
        $map = [
            'shop_id' => $data['shop_id'],
            'cat_id' => $data['id'],
            'hd_status' => 1
        ];
        $res = Db::name('banner')->where($map)->select();
        $title = Db::name('banner_list')->where(['id'=>$data['id']])->field('title')->find();
        $count = count_list('banner');
        $this->assign('count',$count);
        $this->assign('list',$res);
        $this->assign('title',$title['title']);
        $this->assign('shop_id',$data['shop_id']);
        return view();
    }

    //新增轮播图
    public function banner_add_list(){
        if(input('post.')){
            $data = input('post.');
            $data['created'] = time();
            $data['image'] = base64_img($data['image']);
            unset($data['img_url']);
            $res = Db::name('banner')->insert($data);
            if($res){
                return true;
            }else{
                return false;
            }
        }else{
            $info = Db::name('banner_list')->where(['hd_status'=>1,'status'=>1])->select();
            $this->assign('info',$info);
        }
        return view();
    }

}