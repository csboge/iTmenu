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
        //获得商店id
        $shop     = $this->p_auth->getShopId();

        $where = input('param.');
        if(empty($where))return jsonData(404, '未接收到数据', null);
        if($where['cat'] == 1) {
            $map = [
                'shop_id' => $shop,
                'cat_id' => $where['cat'],
                'status' => 1,
                'hd_status' => 1
            ];
        }else{
            $map = [
                'shop_id' => $shop,
                'cat_id' => array(['=',2],['=',3],'or') ,
                'status' => 1,
                'hd_status' => 1
            ];
        }
        $data = Db::name('banner')->where($map)->field('id,image,url,cat_id')->select();
        if($data){
            if($where['cat'] == 1) {
                foreach ($data as &$volue) {
                    $volue['image'] = ImgUrl($volue['image']);
                }
            }else{
                foreach ($data as $volue){
                    $volue['image'] = ImgUrl($volue['image']);
                    if($volue['cat_id'] == 2){
                        $res['shop'][] = $volue;
                    }else{
                        $res['discount'][] = $volue;
                    }
                }
                $data = $res;
            }
            return jsonData(1, 'OK', $data);
        }else{
            return jsonData(405, '未查到到数据', null);
        }

    }
}