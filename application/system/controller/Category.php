<?php
namespace app\system\controller;

use think\Request;
use think\Db;

/**
 * 后台接口 * 菜品分类 * 操作方法
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
     * @参数 hd_status    是否隐藏
     */
    public function add(){
        $data = input('param.');
        if(empty($data)){
            return json_encode(['code'=>0,'message'=>'未接收到数据','data'=>'','status'=>404]);
        }
        $data['created'] = time();
        $res = Db::name('category')->insert($data);
        if($res){
            return json_encode(['code'=>1,'message'=>'OK','data'=>'','status'=>200]);
        }else{
            return json_encode(['code'=>0,'message'=>'数据添加失败','data'=>'','status'=>202]);
        }
    }

    /***
     * 修改 -- 菜品分类
     * @参数 id           id
     * @参数 name         分类名称
     * @参数 parent_id    父级id 0为顶级
     * @参数 rank         排序 asc
     * @参数 shop_id      店铺id
     * @参数 hd_status    是否隐藏
     */
    public function update(){
        $data = input('param.');
        if(empty($data)){
            return json_encode(['code'=>0,'message'=>'未接收到数据','data'=>'','status'=>404]);
        }
        $data['updated'] = time();
        $res = Db::name('category')->where('id',$data['id'])->update($data);
        if($res){
            return json_encode(['code'=>1,'message'=>'OK','data'=>'','status'=>200]);
        }else{
            return json_encode(['code'=>0,'message'=>'数据添加失败','data'=>'','status'=>202]);
        }
    }

    /***
     * 查看 -- 菜品分类
     * @参数 shop_id      店铺id
     */
    public function category(){
        $where = input('param.');
        if(empty($where)){
            return json_encode(['code'=>0,'message'=>'未接收到数据','data'=>'','status'=>404]);
        }
        $map = [
            'shop_id' => $where['shop'],
            'parent_id' => 0,
            'status' => 1,
            'hd_status' => 1
        ];
        $data = Db::name('category')->where($map)->field('id,parent_id,name')->order('id desc')->select();
        if($data){
            foreach ($data as &$volue){
                $volue['list'] = grt_category('category','parent_id',$volue['id']);
            }
            return json_encode(['code'=>1,'message'=>'OK','data'=>$data,'status'=>200]);
        }else{
            return json_encode(['code'=>0,'message'=>'数据查看失败','data'=>'','status'=>202]);
        }
    }



}