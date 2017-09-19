<?php
namespace app\system\controller;

use think\Request;
use think\Db;

/**
 * 后台公共接口
 *
 *
 *
 */
class User
{

    use \app\core\traits\ProviderFactory;

    private     $p_auth;

    /***
     * 注入依赖
     *
     */
    public function __construct(
        Request                         $request,
        \app\core\provider\Auth         $p_auth,
        \app\core\model\User            $m_user,
        \app\core\model\UserAdmin       $m_useradmin
    )
    {
        //验证授权合法
        $p_auth->check($request, [
            'public' => ['*'],
            'private'=> []
        ]);

        //授权服务
        $this->p_auth           = $p_auth;

        //用户模型
        $this->m_user           = $m_user;

        //管理员模型
        $this->m_useradmin      = $m_useradmin;
    }

    /***
     * 验证 -- 商家管理员
     * @参数 mobile           用户手机号
     * @参数 password         密码
     */
    public function isAdmin(){
        $data = input('param.');
        if(empty($data)){
            return ajaxSuccess('未接收到数据');
        }
        $admin = $this->m_useradmin->isUserAdmin($data['mobile'])->toArray();
        if(empty($admin)){
            return ajaxSuccess('您不是管理员');
        }

        print_r($admin);exit;

        $res = $this->m_user->getUserMobile($data['mobile']);
        $is_admin = $this->m_useradmin->isUserAdmin($res['openid']);
        if($is_admin){
            return json_encode(['code'=>1,'message'=>'OK','data'=>$is_admin],true);
        }else{
            return json_encode(['code'=>0,'message'=>'未查到数据','data'=>null],true);
        }
    }
}