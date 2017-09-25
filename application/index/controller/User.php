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
use think\Loader;
use app\index\model\Shop;
use app\index\model\User as Users;


class User extends Controller
{


    //用户列表
    public function index()
    {
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
                $map['nickname'] = array('like', "%{$username}%");
            }
        }
        $map['is_admin'] = 0;
        $data = Db::name('user')->where($map)->order('id desc')->paginate(10);
        $count = count_user('user','is_admin',0);
        // 获取分页显示
        $page = $data->render();
        // 模板变量赋值
        $this->assign('page', $page);
        $this->assign('count', $count);
        $this->assign('list', $data);
        return view();
    }


    //管理员列表
    public function admin_index()
    {
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
                $map['nickname'] = array('like', "%{$username}%");
            }
        }
        $data = Db::name('user_admin')->where($map)->order('status desc')->order('id desc')->paginate(10);
        $count = count_user('user_admin');
        // 获取分页显示
        $page = $data->render();
        // 模板变量赋值
        $this->assign('page', $page);
        $this->assign('count', $count);
        $this->assign('list', $data);
        return view();
    }

    //设置管理员
    public function is_admin(){
        if (input('param.')) {
            $dbs = Db::name('user_admin');
            $data = input('post.');

            if($data['password'] !== $data['repassword']){
                return -1;
            }
            unset($data['repassword']);//验证完成后删除重复密码

            $data['password'] = tplus_ucenter_md5($data['password'],config('auth_key'));//加密

            $shop = new Shop();
            $shop_id = $shop->isShop($data['shop']);
            if ($shop_id == 0) {
                return -2;
            }
            $user = new Users();
            $db = $user->userList($data['id']);
            $ma = [
                'user_id'       => $data['id'],
                'shop_id'       => $data['shop'],
                'password'      => $data['password'],
                'mobile'        => $data['mobile'],
                'nickname'      => $db['nickname'],
                'sex'           => $db['sex'],
                'openid'        => $db['openid'],
                'city'          => $db['city'],
                'province'      => $db['province'],
                'status'        => 1,
                'created'       => time()
            ];

            $a = json_encode($ma,true);
            Db::startTrans();
            try {
                $user_admin = $dbs->insert($ma);
                $user_list  = $user->userAdmin($data['id']);
                if(!$user_admin || !$user_list){
                    Db::rollback();

                    my_log('user_admin',$user_list,'user/admin_index1','-1',$a);
                    return -3;
                }
                Db::commit();
                return true;
            }catch(\Exception $e){
                Db::rollback();
                my_log('user_admin',$data['id'],'user/admin_index2','-1',$a);
                return -4;
            }
        }else{
            return -5;
        }
    }
}