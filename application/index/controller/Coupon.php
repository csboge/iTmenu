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


class Coupon extends Controller
{
    //优惠券列表
    public function index(){
        $data = Db::name('coupon')->order('id desc')->where('hd_status',1)->paginate(100);
        $count = count_list('coupon');
        $this->assign('count',$count);
        $this->assign('list',$data);
        return view();
    }

    //新增优惠券
    public function add(){
        if(input('post.')){
            $data = input('post.');
            $data['created'] = time();
            $res = Db::name('coupon')->insert($data);
            if($res){
                return true;
            }else{
                return false;
            }
        }else{
            $info = Db::name('coupon')->where('id<0')->select();
            $list = Db::name('shop')->where(['hd_status'=>1,'status'=>1])->select();
            $this->assign('info',$info);
            $this->assign('list',$list);
        }
        return view();
    }


    //修改优惠券显示页
    public function update(){
        $data = input('id');
        if(empty($data))return false;
        $db = Db::name('coupon')->where('id',$data)->find();
        $list = Db::name('shop')->where(['hd_status'=>1,'status'=>1])->select();
        $this->assign('list',$list);
        $this->assign('info',$db);
        return view();
    }

    //修改操作页
    public function is_update(){
        $db = Db::name('coupon');
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