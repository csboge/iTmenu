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


class Goods extends Controller
{
    //菜品分类列表
    public function index(){
        $data = Db::name('category')->order('id ASC')->where('hd_status',1)->paginate(100);
        $count = count_list('category');
        $this->assign('count',$count);
        $this->assign('list',$data);
        return view();
    }

    //新增菜品分类
    public function add(){
        if(input('post.')){
            $data = input('post.');
            $data['created'] = time();
            $res = Db::name('category')->insert($data);
            if($res){
                return true;
            }else{
                return false;
            }
        }else{
            $list = Db::name('shop')->where(['hd_status'=>1,'status'=>1])->select();
            $info = Db::name('category')->where(['parent_id'=>0,'hd_status'=>1,'status'=>1])->select();
            $this->assign('info',$info);
            $this->assign('list',$list);
        }
        return view();
    }


    //修改菜品分类显示页
    public function update(){
        $data = input('param.');
        if(empty($data))return false;
        $db = Db::name('category')->where('id',$data['id'])->find();
        $list = Db::name('shop')->where(['hd_status'=>1,'status'=>1])->select();
        $info = Db::name('category')->where(['parent_id'=>0,'hd_status'=>1,'status'=>1])->select();
        $this->assign('info',$info);
        $this->assign('list',$list);
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


    //菜品列表
    public function goods_index(){
        $data = Db::name('goods')->order('id ASC')->where('hd_status',1)->paginate(10);
        $count = count_list('goods');
        $this->assign('count',$count);
        $this->assign('list',$data);
        return view();
    }

    //新增菜品
    public function goods_add(){
        if(input('post.')){
            $data = input('post.');
            foreach ($data['titles'] as $key=>$volue){
                if($volue){
                    $data['attrs'][$key]['titles'] = $volue;
                    $data['attrs'][$key]['prices'] = $data['prices'][$key];
                }
            }
            $data['attrs'] = serialize($data['attrs']);
            $data['image'] = base64_img($data['image']);
            $data['created'] = time();
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
            $list = Db::name('shop')->where(['hd_status'=>1,'status'=>1])->select();
            $info = Db::name('category')->where(['hd_status'=>1,'status'=>1])->select();
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
        $db['attrs'] = unserialize($db['attrs']);
        $db['attrs'] = json_encode($db['attrs'],true);
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
            foreach ($data['titles'] as $key=>$volue){
                if($volue){
                    $data['attrs'][$key]['titles'] = $volue;
                    $data['attrs'][$key]['prices'] = $data['prices'][$key];
                }
            }
            $data['attrs'] = array_merge($data['attrs']);
            $data['attrs'] = serialize($data['attrs']);
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


}