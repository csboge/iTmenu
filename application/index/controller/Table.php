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
        $data = Db::name('table_list')->order('id ASC')->where('hd_status',1)->paginate(10);
        $count = count_list('table_list');
        $this->assign('count',$count);
        $this->assign('list',$data);
        return view();
    }

    //新增桌子分类
    public function add(){
        if(input('post.')){
            $data = input('post.');
            $data['created'] = time();
            $res = Db::name('table_list')->insert($data);
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


    //修改桌子分类显示页
    public function update(){
        $data = input('param.');
        if(empty($data))return false;
        $db = Db::name('table_list')->where('id',$data['id'])->find();
        $list = Db::name('shop')->where(['hd_status'=>1,'status'=>1])->select();
        $this->assign('list',$list);
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
        $data = Db::name('table')->order('id ASC')->where('hd_status',1)->paginate(10);
        $count = count_list('table');
        $this->assign('count',$count);
        $this->assign('list',$data);
        return view();
    }

    //新增桌子
    public function table_add(){
        if(input('post.')){
            $data = input('post.');
            $data['created'] = time();
            unset($data['img_url']);
            $data['image'] = base64_img($data['image']);
            $res = Db::name('table')->insert($data);
            if($res){
                return true;
            }else{
                return false;
            }
        }else{
            $list = Db::name('shop')->where(['hd_status'=>1,'status'=>1])->select();
            $info = Db::name('table_list')->where(['hd_status'=>1,'status'=>1])->select();
            $this->assign('info',$info);
            $this->assign('list',$list);
        }
        return view();
    }


    //修改桌子显示页
    public function table_update(){
        $data = input('param.');
        if(empty($data))return false;
        $db = Db::name('table')->where('id',$data['id'])->find();
        $db['image'] = ImgUrl($db['image']);
        $info = Db::name('table_list')->where(['hd_status'=>1,'status'=>1])->select();
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
            $data['image'] = base64_img($data['image']);
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


}