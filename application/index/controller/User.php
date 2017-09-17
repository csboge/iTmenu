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


class User extends Controller
{
    //用户列表
    public function index()
    {
        $where = input('param.');
        $map = [];
        if ($where) {
            $start = strtotime($where['start']);
            $end = strtotime($where['end']);
            $username = $where['username'];
            if ($start) {
                $map['created'] = ['>', $start];
            }
            if ($end) {
                $map['created'] = ['<', $end];
            }
            if ($username) {
                $map['nickname'] = ['like', '%' . $username . '%'];
            }
        }
//        $map['is_admin'] = 0;
        $data = Db::name('user')->where($map)->order('id desc')->limit(1, 100)->select();
        $count = count_user('user','is_admin',0);
        $this->assign('count', $count);
        $this->assign('list', $data);
        return view();
    }


    //管理员列表
    public function admin_index()
    {
        if (input('param.')) {
            $where = input('param.');
            $shop = new \app\index\model\Shop();
            $shop_id = $shop->isShop($where['shop']);
            if ($shop_id == 0) {
                return false;
            }
            $user = new \app\index\model\User();
            $db = $user->userList($where['id']);
            $ma = [
                'user_id'       => $where['id'],
                'nickname'      => $db['nickname'],
                'sex'           => $db['sex'],
                'openid'        => $db['openid'],
                'shop_id'       => $where['shop'],
                'mobile'        => $where['mobile'],
                'city'          => $db['city'],
                'province'      => $db['province'],
                'status'        => 1,
                'created'       => time()
            ];

            $a = json_encode($ma,true);
            my_log('user_admin',$where['id'],'user/admin_index','0',$a);
            Db::startTrans();
            try {
                my_log('user_admin',$where['id'],'user/admin_index','-1',$a);
                $user_admin = Db::name('user_admin')->insert($ma);
                my_log('user_admin',$where['id'],'user/admin_index','-2',$a);
                $user_list  = $user->userAdmin($where['id']);

//                if(!$user_admin || !$user_list){
//                    Db::rollback();
//                    return false;
//                }

                my_log('user_admin',$user_admin,'user/admin_index','-5',$a);
                my_log('user_admin',$user_list,'user/admin_index','-4',$where['id']);
                Db::commit();
                return true;
            }catch(\Exception $e){
                Db::rollback();
                my_log('user_admin',$where['id'],'user/admin_index','-3',$a);
                return false;
            }
        }
//        $map = [
//            'hd_status'        => 1,
//        ];
        $data = Db::name('user_admin')->order('status desc')->order('id desc')->select();
        $count = count_user('user_admin');
        $this->assign('count', $count);
        $this->assign('list', $data);
        return view();
    }

}