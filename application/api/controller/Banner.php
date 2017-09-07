<?php
namespace app\api\controller;

use think\Request;
use think\Db;

class Banner
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
     * 查看 - 轮播图
     * @参数 shop    商户id
     * @参数 cat     类型
     */
    public function banner_hongbao(){
        $where = input('param.');
        if(empty($where))return jsonData(404, '未接收到数据', null);
        $map = [
            'shop_id' => $where['shop'],
            'cat_id' => $where['cat'],
            'status' => 1,
            'hd_status' => 1
        ];
        $data = Db::name('banner')->where($map)->field('id,image,url')->select();
        if($data){
            foreach ($data as &$volue){
                $volue['image'] = ImgUrl($volue['image']);
            }
            return jsonData(1, 'OK', $data);
        }else{
            return jsonData(405, '未查到到数据', null);
        }

    }
}