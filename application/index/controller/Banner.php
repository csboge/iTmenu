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


class Banner extends Controller
{
    //轮播图列表
    public function index(){
        $type = input('param.');
        if(empty($type))return false;
        $map = [
            'hd_status' => 1,
            'cat_id' => $type['cat_id'],
            'shop_id' => session('shop_id')
        ];
        $res = Db::name('banner')->where($map)->order('id desc')->select();
        $count = count_list('banner','shop_id',session('shop_id'));
        $this->assign('count',$count);
        $this->assign('cat_id',$type['cat_id']);
        $this->assign('list',$res);
        return view();
    }


    //新增轮播图显示
    public function add(){
        $data = input('param.');
        if(empty($data))return false;
        $this->assign('cat_id',$data['cat_id']);
        return view();
    }

    //新增轮播图
    public function is_add(){
        if(input('post.')){
            $data = input('post.');
            $data['created'] = time();
            $data['shop_id'] = session('shop_id');
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

    //修改轮播图显示页
    public function update(){
        $data = input('param.');
        if(empty($data))return false;
        $db = Db::name('banner')->where('id',$data['id'])->find();
        $db['image'] = ImgUrl($db['image']);
        $list = Db::name('shop')->where(['hd_status'=>1,'status'=>1])->select();
        $this->assign('info',$list);
        $this->assign('vo',$db);
        $this->assign('cat_id',$data['cat_id']);
        return view();
    }

    //修改轮播图操作
    public function is_update(){
        if(input('post.')){
            $data = input('post.');
            $data['updated'] = time();;
            unset($data['img_url']);
            if($data['image']){
                $data['image'] = base64_img($data['image']);
            }else{
                unset($data['image']);
            }
            $db = Db::name('banner');
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
        $this->assign('title',$title);
        return view();
    }
}