<?php
namespace app\api\controller;

use think\Request;
use think\Db;

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
     * @参数 title    商户id
     */
    function config()
    {
        $title     = input('param.title/s');
        if(empty($title))return jsonData(404, '未接收到数据', null);
        $map = [
            'id' => $title,
            'status' => 1,
            'hd_status' => 1
        ];
        $data = Db::name('shop')->where($map)->field('id,logo,notice,mobile')->find();
        if($data){
            $data['logo'] = ImgUrl($data['logo']);
            return jsonData(1, 'OK', $data);
        }else{
            return jsonData(405, '未查到到数据', null);
        }
    }



}