<?php
namespace app\api\controller;

use think\Request;

class Shop
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
     * 商户 - 信息设置
     */
    function config()
    {
        
        $title     = input('param.title/s');


        

        $info = array(

            'title' => $title,

        );








        return jsonData(1, 'ok1111', null);
    }



}