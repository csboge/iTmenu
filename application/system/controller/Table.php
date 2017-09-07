<?php
namespace app\system\controller;

use think\Request;
use think\Db;

/**
 * 后台桌子接口
 *
 *
 *
 */
class Table
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
     * 新增 -- 桌子分类
     * @参数 name          分类名
     * @参数 shop_id       店铺id
     */
    public function list_add(){
        $data = input('param.');
        if(empty($data)){
            return json_encode(['code'=>0,'message'=>'未接收到数据','data'=>'','status'=>404]);
        }
        $data['created'] = time();
        $res = Db::name('table_list')->insert($data);
        if($res){
            return json_encode(['code'=>1,'message'=>'OK','data'=>'','status'=>200]);
        }else{
            return json_encode(['code'=>0,'message'=>'数据添加失败','data'=>'','status'=>202]);
        }
    }


    /***
     * 修改 -- 桌子分类
     * @参数 id            id
     * @参数 name          分类名
     */
    public function list_update(){
        $data = input('param.');
        if(empty($data)){
            return json_encode(['code'=>0,'message'=>'未接收到数据','data'=>'','status'=>404]);
        }
        $data['updated'] = time();
        $res = Db::name('table_list')->where(['id'=>$data['id']])->update($data);
        if($res){
            return json_encode(['code'=>1,'message'=>'OK','data'=>'','status'=>200]);
        }else{
            return json_encode(['code'=>0,'message'=>'数据修改失败','data'=>'','status'=>202]);
        }
    }


    /***
     * 查看 -- 桌子分类
     * @参数 shop_id      店铺id
     */
    public function list_table(){
        $data = input('param.');
        if(empty($data)){
            return json_encode(['code'=>0,'message'=>'未接收到数据','data'=>'','status'=>404]);
        }
        $map = [
            'shop_id' => $data['shop_id'],
            'status' => 1,
            'hd_status' => 1
        ];
        $res = Db::name('table_list')->where($map)->field('id,name,created')->order('id desc')->select();
        if($res){
            foreach ($res as &$volue){
                $volue['created'] = date('y-m-d H:i:s',$volue['created']);
            }
            return json_encode(['code'=>1,'message'=>'OK','data'=>$res,'status'=>200]);
        }else{
            return json_encode(['code'=>0,'message'=>'数据添加失败','data'=>'','status'=>202]);
        }
    }

    /***
     * 新增 -- 桌子
     * @参数 shop_id      店铺id
     * @参数 cat_id       分类id
     * @参数 table_id     桌子编号
     * @参数 name         名称
     * @参数 image        图片id
     * @参数 minimum      最低消费
     */
    public function add(){
        $data = input('param.');
        if(empty($data)){
            return json_encode(['code'=>0,'message'=>'未接收到数据','data'=>'','status'=>404]);
        }
        $data['created'] = time();
        $res = Db::name('table')->insert($data);
        if($res){
            return json_encode(['code'=>1,'message'=>'OK','data'=>'','status'=>200]);
        }else{
            return json_encode(['code'=>0,'message'=>'数据添加失败','data'=>'','status'=>202]);
        }
    }

    /***
     * 修改 -- 桌子
     * @参数 id           id
     * @参数 cat_id       分类id
     * @参数 table_id     桌子编号
     * @参数 name         名称
     * @参数 image        图片id
     * @参数 minimum      最低消费
     */
    public function update(){
        $data = input('param.');
        if(empty($data)){
            return json_encode(['code'=>0,'message'=>'未接收到数据','data'=>'','status'=>404]);
        }
        $data['updated'] = time();
        $res = Db::name('table')->where(['id'=>$data['id']])->update($data);
        if($res){
            return json_encode(['code'=>1,'message'=>'OK','data'=>'','status'=>200]);
        }else{
            return json_encode(['code'=>0,'message'=>'数据修改失败','data'=>'','status'=>202]);
        }
    }

    /***
     * 查看 -- 桌子
     * @参数 shop_id      店铺id
     * @参数 cat_id       分类id
     */
    public function table(){
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
        $res = Db::name('table')->where($map)->field('id,table_id,name,image,minimum,reception')->order('id desc')->select();
        if($res){
            foreach ($res as &$volue){
                $volue['image'] = ImgUrl($volue['image']);
            }
            return json_encode(['code'=>1,'message'=>'OK','data'=>$res,'status'=>200]);
        }else{
            return json_encode(['code'=>0,'message'=>'数据查找失败','data'=>'','status'=>202]);
        }
    }
}