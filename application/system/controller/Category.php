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
            return jsonDataList(0,'未接收到数据',[]);
        }
        $data['created'] = time();
        $res = Db::name('category')->insert($data);
        if($res){
            return jsonDataList(1,'OK',[]);
        }else{
            return jsonDataList(0,'数据添加失败',[]);
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
            return jsonDataList(0,'未接收到数据',[]);
        }
        $data['updated'] = time();
        $res = Db::name('category')->where('id',$data['id'])->update($data);
        if($res){
            return jsonDataList(1,'OK',[]);
        }else{
            return jsonDataList(0,'数据添加失败',[]);
        }
    }

    /***
     * 查看 -- 菜品分类
     * @参数 shop_id      店铺id
     */
    public function category(){
        $where = input('param.');
        if(empty($where)){
            return jsonDataList(0,'未接收到数据',[]);
        }
        $map = [
            'shop_id' => $where['shop_id'],
            'parent_id' => 0,
            'status' => 1,
            'hd_status' => 1
        ];
        $data = Db::name('category')->where($map)->field('id,parent_id,name')->order('id desc')->select();

        if($data){
            foreach ($data as &$volue){
                $volue['list'] = grt_category('category','parent_id',$volue['id']);
            }
            return jsonDataList(1,'OK',$data);
        }else{
            return jsonDataList(1,'数据查看失败',[]);
        }
    }
}