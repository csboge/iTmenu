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
class Menu
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
     * 新增 -- 菜品
     * @参数 name         数据库名
     * @参数 id           id
     * @参数 status       字段名
     * @参数 value        修改的值
     */
    public function get_status(){
        $data = input('post.');
        if(empty($data)){
            return json_encode(['code'=>0,'message'=>'未接收到数据','data'=>'']);
        }
        $res = Db::name($data['name'])->where('id',$data['id'])->setField($data['status'],$data['value']);
        if($res){
            return json_encode(['code'=>1,'message'=>'OK','data'=>'']);
        }else{
            return json_encode(['code'=>0,'message'=>'数据添加失败','data'=>'']);
        }
    }


}