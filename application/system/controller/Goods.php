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
class Goods
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
     * @参数 title         商品标题
     * @参数 shop_id       店铺id
     * @参数 cat_id        分类id
     * @参数 image         商品封面
     * @参数 rank          排序 asc
     * @参数 attrs         规格（序列化格式）
     * @参数 price         商品价格
     * @参数 status        1：启用 0：禁用
     */
    public function add(){
        $data = input('param.');
        if(empty($data)){
            return json_encode(['code'=>0,'message'=>'未接收到数据','data'=>'','status'=>404]);
        }
        $data['created'] = time();
        $res = Db::name('goods')->insert($data);
        if($res){
            return json_encode(['code'=>1,'message'=>'OK','data'=>'','status'=>200]);
        }else{
            return json_encode(['code'=>0,'message'=>'数据添加失败','data'=>'','status'=>202]);
        }
    }


    /***
     * 修改 -- 菜品
     * @参数 id            id
     * @参数 title         商品标题
     * @参数 shop_id       店铺id
     * @参数 cat_id        分类id
     * @参数 image         商品封面
     * @参数 rank          排序 asc
     * @参数 attrs         规格（序列化格式）
     * @参数 price         商品价格
     */
    public function update(){
        $data = input('param.');
        if(empty($data)){
            return json_encode(['code'=>0,'message'=>'未接收到数据','data'=>'','status'=>404]);
        }
        $data['updated'] = time();
        $res = Db::name('goods')->where(['id'=>$data['id']])->update($data);
        if($res){
            return json_encode(['code'=>1,'message'=>'OK','data'=>'','status'=>200]);
        }else{
            return json_encode(['code'=>0,'message'=>'数据添加失败','data'=>'','status'=>202]);
        }
    }


    /***
     * 查看 -- 菜品
     * @参数 shop_id      店铺id
     * @参数 cat_id       分类id
     */
    public function goods(){
        $data = input('param.');
        if(empty($data)){
            return json_encode(['code'=>0,'message'=>'未接收到数据','data'=>'','status'=>404]);
        }
        $map = [
            'shop_id' => $data['shop_id'],
            'cat_id' => $data['cat_id'],
            'status' => 1,
            'hd_status' => 1
        ];
        $res = Db::name('goods')->where($map)->field('id,title,image,sale,attrs,price')->order('id desc')->select();
        if($res){
            foreach ($res as &$volue){
                $volue['image'] = ImgUrl($volue['image']);
                $volue['attrs'] = unserialize($volue['attrs']);
            }
            return json_encode(['code'=>1,'message'=>'OK','data'=>$res,'status'=>200]);
        }else{
            return json_encode(['code'=>0,'message'=>'数据添加失败','data'=>'','status'=>202]);
        }
    }
}