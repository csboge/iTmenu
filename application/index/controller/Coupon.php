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
        $map = [
            'hd_status' => 1,
            'shop_id' => session('shop_id')
        ];
        $data = Db::name('coupon')->order('id desc')->where($map)->paginate(100);
        $count = count_list('coupon','shop_id',session('shop_id'));
        $title = session('shop_title');
        $this->assign('title',$title);
        $this->assign('count',$count);
        $this->assign('list',$data);
        return view();
    }

    //新增优惠券
    public function add(){
        if(input('post.')){
            $data = input('post.');
            $data['start_time'] = strtotime($data['start_time']);
            $data['end_time'] = strtotime($data['end_time']);
            $data['created'] = time();
            $data['shop_id'] = session('shop_id');
            $res = Db::name('coupon')->insert($data);
            if($res){
                return true;
            }else{
                return false;
            }
        }else{
            $info = date('Y-m-d',time());
            $this->assign('info',$info);
        }
        return view();
    }


    //修改优惠券显示页
    public function update(){
        $data = input('id');
        if(empty($data))return false;
        $db = Db::name('coupon')->where('id',$data)->find();
        $db['start_time'] = date('Y-m-d',$db['start_time']);
        $db['end_time'] = date('Y-m-d',$db['end_time']);
        $this->assign('info',$db);
        return view();
    }

    //修改操作页
    public function is_update(){
        $db = Db::name('coupon');
        if(input('post.')){
            $data = input('post.');
            $data['updated'] = time();
            $data['start_time'] = strtotime($data['start_time']);
            $data['end_time'] = strtotime($data['end_time']);
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