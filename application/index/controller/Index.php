<?php
namespace app\index\controller;

use app\index\model\User;
use think\Controller;
use think\Request;

class Index
{

    /***
     * 注入依赖
     *
     */
    public function __construct(
        Request                         $request,
        \app\core\provider\User         $p_user,
        \app\core\model\UserAdmin       $m_useradmin,
        \app\core\provider\Auth         $p_auth
    )
    {
        //验证授权合法
        /*$p_auth->check($request, [
            'public' => [],
            'private'=> ['*']
        ]);*/

        //获得授权信息
        //$this->session  = $p_auth->session();

        //用户服务
        $this->p_user           = $p_user;
        $this->m_useradmin      = $m_useradmin;

    }

    public function index()
    {
        if(session('username') == 'admin'){
            return view();
        }
        return view('login');
    }


    public function login()
    {

        if(input('post.')){

            $data = input('post.');

            if(empty($data))
            {
                return json(array('code'=>-1,'message'=>'传输错误，请重新尝试'));
            }

            $admin = $this->m_useradmin->isUserName($data['username']);

            if(empty($admin))
            {
                return json(array('code'=>-2,'message'=>'用户名错误'));
            }

            if($admin['type'] !== 1){
                return json(array('code'=>-3,'message'=>'不是管理员'));
            }
            $rand = $admin['rand'];

            $password = tplus_ucenter_md5($data['password'],$rand);//加密

            if($admin['password'] !== $password)
            {
                return json(array('code'=>-4,'message'=>'密码错误'));
            }

            session('username',$data['username']);

            return json(array('code'=>1,'message'=>'登录成功'));
        }

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
