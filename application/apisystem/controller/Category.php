<?php
namespace app\apisystem\controller;

use think\Request;
use think\Db;

/**
 * 后台接口 * 分类 * 操作方法
 *
 *
 *
 */
class Category
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
     * 新增 -- 菜品分类
     * @参数 name         分类名称
     * @参数 parent_id    父级id 0为顶级
     * @参数 rank         排序 asc
     * @参数 shop_id      店铺id
     * @参数 status       是否隐藏
     */
    public function add(){
        $data = input('post.');
        if(empty($data)){
           return json_encode(['code'=>0,'message'=>'未接收到数据','data'=>'']);
        }
        $data['created'] = time();
        $res = Db::name('category')->insert($data);
        if($res){
            return json_encode(['code'=>1,'message'=>'OK','data'=>'']);
        }else{
            return json_encode(['code'=>0,'message'=>'数据添加失败','data'=>'']);
        }
    }
}