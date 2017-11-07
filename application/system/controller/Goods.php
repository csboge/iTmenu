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
     * @参数 attrs         规格（json）
     * @参数 price         商品价格
     * @参数 status        1：启用 0：禁用
     */
    public function add(){
        $data = input('param.');
        if(empty($data)){
            return jsonDataList(0,'未接收到数据',[]);
        }
        $data['created'] = time();
        $res = Db::name('goods')->insert($data);
        if($res){
            return jsonDataList(1,'OK',[]);
        }else{
            return jsonDataList(0,'数据添加失败',[]);
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
     * @参数 attrs         规格（json）
     * @参数 price         商品价格
     */
    public function update(){
        $data = input('param.');
        if(empty($data)){
            return jsonDataList(0,'未接收到数据',[]);
        }
        $data['updated'] = time();
        $res = Db::name('goods')->where(['id'=>$data['id']])->update($data);
        if($res){
            return jsonDataList(1,'OK',[]);
        }else{
            return jsonDataList(0,'数据修改失败',[]);
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
            return jsonDataList(0,'未接收到数据',[]);
        }
        $map = [
            'shop_id' => $data['shop_id'],
            'hd_status' => 1
        ];
        $limit = $data['limit']?$data['limit']:10;
        $page = ($data['page']-1)*$limit;
        $res = Db::name('goods')
            ->where($map)
            ->field('id,title,cat_id,rank,image,sale,attrs,price,bowl,intro,status,sd_status,hd_status')
            ->order('rank asc,status')
            ->order('id desc')
            ->limit($page,$limit)
            ->select();
        $count = count_list('goods','shop_id',$data['shop_id']);
        if($res){
            foreach ($res as &$value){
                $value['img']   = $value['image'];
                $value['image'] = ImgUrl($value['image']);
                $value['attrs'] = json_decode($value['attrs'],true);
                $new_arr = multiToSingle($value['attrs']);          //降维处理
                if($new_arr[0] == ''){
                    $value['attrs'] = [];
                }
            }
            $rew = [
                'list' => $res,
                'count' => $count
            ];
            return jsonDataList(1,'OK',$rew);
        }else{
            return jsonDataList(0,'未查到数据',[]);
        }
    }

    /***
     * 添加 -- 菜品类型
     */
    public function add_type(){
        $data = input('param.');
        if(empty($data)){
            return jsonDataList(0,'未接收到数据',[]);
        }
        $res = Db::name('type_goods')->insert($data);
        if($res){
            return jsonDataList(1,'OK',[]);
        }else{
            return jsonDataList(0,'数据添加失败',[]);
        }
    }

    /***
     * 修改 -- 菜品类型
     */
    public function update_type(){
        $data = input('param.');
        if(empty($data)){
            return jsonDataList(0,'未接收到数据',[]);
        }
        $res = Db::name('type_goods')->where(['id'=>$data['id']])->update($data);
        if($res){
            return jsonDataList(1,'OK',[]);
        }else{
            return jsonDataList(0,'数据修改失败',[]);
        }
    }

    /***
     * 查看 -- 菜品类型
     * @参数 shop_id      店铺id
     */
    public function lock(){
        $data = input('param.shop_id');
        if(empty($data)){
            return jsonDataList(0,'未接收到数据',[]);
        }
        $res = Db::name('type_goods')->where('shop_id',$data)->select();
        if($res){
            return jsonDataList(1,'OK',$res);
        }else{
            return jsonDataList(0,'数据查看失败',[]);
        }
    }

    /***
     * 删除 -- 菜品类型
     * @参数 id      菜品类型id
     */
    public function delete(){
        $data = input('param.id');
        if(empty($data)){
            return jsonDataList(0,'未接收到数据',[]);
        }
        $res = Db::name('type_goods')->where('id',$data)->delete();
        if($res){
            return jsonDataList(1,'OK',[]);
        }else{
            return jsonDataList(0,'删除成功',[]);
        }
    }
}