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
            return ajaxSuccess(0,'未接收到数据',null);
        }
        $admin = $this->m_useradmin->isUserAdmin($data['mobile']);
        if(empty($admin)){
            return ajaxSuccess(0,'您不是管理员',null);
        }
        $admin = $admin->toArray();

        $data['password'] = tplus_ucenter_md5($data['password'],config('auth_key'));//加密
        if($admin['password'] !== $data['password']){
            return ajaxSuccess(0,'密码错误',null);
        }
        $res = [
            'shop_id' => $admin['shop_id'],
            'user_id' => $admin['id']
        ];
        return ajaxSuccess(1,'登录成功',$res);
    }


}