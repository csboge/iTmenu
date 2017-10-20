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
class Banner
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
     * 新增 -- 轮播图
     * @参数 shop_id       店铺id
     * @参数 cat_id        分类id
     * @参数 image         商品封面
     * @参数 url           图片链接
     */
    public function add(){
        $data = input('param.');
        if(empty($data)){
            return jsonDataList(0,'未接收到数据',[]);
        }
        $data['created'] = time();
        $res = Db::name('banner')->insert($data);
        if($res){
            return jsonDataList(1,'OK',[]);
        }else{
            return jsonDataList(0,'数据添加失败',[]);
        }
    }


    /***
     * 修改 -- 轮播图
     * @参数 id            id
     * @参数 cat_id        分类id
     * @参数 image         商品封面
     * @参数 url           图片链接
     */
    public function update(){
        $data = input('param.');
        if(empty($data)){
            return jsonDataList(0,'未接收到数据',[]);
        }
        $data['updated'] = time();
        $res = Db::name('banner')->where(['id'=>$data['id']])->update($data);
        if($res){
            return jsonDataList(1,'OK',[]);
        }else{
            return jsonDataList(0,'数据修改失败',[]);
        }
    }


    /***
     * 查看 -- 轮播图
     * @参数 shop_id      店铺id
     * @参数 cat_id       分类id
     */
    public function banner(){
        $data = input('param.');
        if(empty($data)){
            return jsonDataList(0,'未接收到数据',[]);
        }
        $map = [
            'shop_id' => $data['shop_id'],
            'cat_id' => $data['cat_id'],
            'status' => 1,
            'hd_status' => 1
        ];
        $res = Db::name('banner')->where($map)->field('id,cat_id,image,url')->order('id desc')->select();
        if($res){
            foreach ($res as &$volue){
                $volue['image'] = ImgUrl($volue['image']);
            }
            return jsonDataList(1,'OK',$res);
        }else{
            return jsonDataList(1,'未查到数据',[]);
        }
    }
}