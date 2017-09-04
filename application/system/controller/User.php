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
        \app\core\provider\Auth         $p_auth
    )
    {
        //验证授权合法
        $p_auth->check($request, [
            'public' => ['*'],
            'private'=> []
        ]);

        //授权服务
        $this->p_auth   = $p_auth;
    }

    /***
     * 验证 -- 商家管理员
     * @参数 openid         微信openid
     */
    public function isAdmin(){
        $data = input('post.');
        if(empty($data)){
            return json_encode(['code'=>0,'message'=>'未接收到数据','data'=>'','status'=>404]);
        }
        $res = Db::name('user')->where(['openid'=>$data['openid']])->find();
        if($res){
            if($res['is_admin'] == 1){
                return json_encode(['code'=>1,'message'=>'OK','data'=>$res['shop_id'],'status'=>200]);
            }else{
                return json_encode(['code'=>0,'message'=>'不是管理员','data'=>'','status'=>203]);
            }
        }else{
            return json_encode(['code'=>0,'message'=>'未查到到数据','data'=>'','status'=>405]);
        }
    }
}