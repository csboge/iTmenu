<?php
namespace app\api\controller;

use think\Request;

class Menu
{
    private     $p_auth;

    /***
     * 公共 - 注入依赖
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
     * 获得 - 菜谱分类
     */
    function category()
    {
        
        return jsonData(1, 'ok', null);
    }


    /***
     * 获得 - 菜谱菜品
     */
    function goods()
    {
        
        $data       = $this->p_auth->session();

        return jsonData(1, 'ok', $data);
    }


}