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
        $this->p_user = $p_user;

    }

    public function index()
    {
        return view();
    }


    public function login()
    {

        $re = $this->p_user->getCode(15084852913);

        var_dump($re);
        exit;
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
