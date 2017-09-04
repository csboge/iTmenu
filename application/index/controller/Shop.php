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
        $this->assign('list',$data);
        return view();
    }

    //新增商店
    public function add(){
        if(input('post.')){
            $data = input('post.');
            $res = Db::name('shop')->insert($data);
            if($res){
                return json_encode(['code'=>1,'message'=>'成功']);
            }else{
                return json_encode(['code'=>0,'message'=>'失败']);
            }
        }else{
            $list = Db::name('shop')->where('id>0')->select();
            $this->assign('list',$list);
        }
        return view();
    }

}