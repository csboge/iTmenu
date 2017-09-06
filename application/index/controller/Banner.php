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
            'cat_id' => $type['type']
        ];
        $res = Db::name('banner')->where($map)->select();
        $count = count_list('banner');
        $this->assign('count',$count);
        $this->assign('list',$res);
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