<?php
namespace app\index\controller;

use app\index\model\User;

class Index
{
    public function index()
    {
        return view();
    }


    public function login()
    {

        return view();

    }


    public function upload()
    {

        return view();
    }


    /**
     * @return \think\response\Json
     * 检查用户名密码
     */
    public function check(){
        $data = input('post.');
        $code=404;
        $msg='登录失败';

        if(!empty($data)){
            $a =new User();
            $ret=$a->where(['mobile'=>$data['username'],'password'=>$data['password']])->count();
            if($ret>0){
                $code=200;
                $msg='登录成功';
            }
        }
        return json(array('code'=>$code,'msg'=>$msg));
    }
}
