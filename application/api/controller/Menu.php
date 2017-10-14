<?php
namespace app\api\controller;

use think\Request;
use think\Db;

/**
 * 菜谱方面 * 服务 * 操作方法
 *
 *
 *
 */
class Menu
{
    private     $p_auth;

    /***
     * 公共 - 注入依赖
     *
     */
    public function __construct(
        Request                         $request,
        \app\core\provider\Auth         $p_auth,
        \app\core\model\TypeGoods       $m_typegoods
    )
    {
        //验证授权合法
        $p_auth->check($request, [
            'public' => ['*'],
            'private'=> []
        ]);

        //授权服务
        $this->p_auth           = $p_auth;

        //类型模型
        $this->m_typegoods      = $m_typegoods;

    }

    /***
     * 获得 - 菜谱分类
     */
    public function category_list(){
        //获得商店id
        $shop     = $this->p_auth->getShopId();

        $map =[
            'shop_id' => $shop,
            'parent_id'=>0,
            'status' => 1,
            'hd_status' => 1,
            'sd_status' => 1
        ];
        $ma =[
            'shop_id' => $shop,
            'sd_status' => 1,
            'hd_status' => 1
        ];
        $res = Db::name('category')->where($map)->field('id,name')->order('rank asc')->select();
        $rew = Db::name('package')->where($ma)->field('id,name')->order('rank asc')->select();
        if($res || $rew){
            foreach ($res as &$value){
                $mad = [
                    'parent_id' => $value['id'],
                    'status' => 1,
                    'hd_status' => 1
                ];
                $dp = Db::name('category')->where($mad)->field('id,name')->order('rank asc')->select();
                $value['list'] = $dp;
            }
            $list['cate_list'] = $res;
            $list['mob_list'] = $rew;
            return jsonData(1, 'OK', $list);
        }else{
            return jsonData(1, '未查到数据', []);
        }
    }

    /***
     * 获取 - 菜品详情
     * @参数 cat_id       分类id (可不传)
     * @参数 package      套餐id (可不传)
     */
    public function goods_list(){
        //获得商店id
        $shop     = $this->p_auth->getShopId();

        $data = input('post.');
        if(empty($data)){
            return jsonData(0, '未接收到数据', '');
        }
        if(!empty($data['package'])){
            $map = [
                'shop_id' => $shop,
                'package' => $data['package'],
                'status' => 1,
                'hd_status' => 1
            ];
        }elseif(!empty($data['cat_id'])){
            $map = [
                'shop_id' => $shop,
                'cat_id' => $data['cat_id'],
                'status' => 1,
                'hd_status' => 1
            ];
        }elseif (empty($data['cat_id']) && empty($data['package'])){
            $map = [
                'shop_id' => $shop,
                'status' => 1,
                'hd_status' => 1
            ];
        }
        $db = Db::name('goods');
        $res = $db
            ->where($map)
            ->field('id,title,image,sale,attrs,price,cat_id,package,rank,bowl,type_id')
            ->order('rank asc')
            ->select();
        if($res){
            foreach ($res as $key=>&$value){
                $value['image'] = ImgUrl($value['image'])?ImgUrl($value['image']):'';
                $value['name'] = $value['title'];
                unset($value['title']);
                if($value['type_id'] == 0){
                    $value['type_id'] = '';
                }else{
                    $value['type_id'] = $this->m_typegoods->typeList($value['type_id']);
                }
                $value['attrs'] = json_decode($value['attrs'],true);
                if(!isset($value['attrs'][$key]['titles'])){
                    $value['attrs'] = [];
                }
            }
            return jsonData(1, 'OK', $res);
        }else{
            return jsonData(1, '未查到数据', '');
        }
    }


    /***
     * 获取 - 提示信息
     * @参数 cat_id       分类id (可不传)
     * @参数 package      套餐id (可不传)
     */
    public function tishi(){
        //获得商店id
        $shop     = $this->p_auth->getShopId();
        $data = [
            'gonggao' => [
                '10号桌点餐牛肉炒面',
                '10号桌点餐牛肉炒面',
                '10号桌点餐牛肉炒面',
                '10号桌点餐牛肉炒面',
            ],
            'avatar' => [
                'https://wx.qlogo.cn/mmopen/vi_32/QXsjJ6a9S6IZacPhoShtGlnzdK2QLefIZl8ezX2Q35lwjictRE6ABlvPCBibASXWB9FvmgezySPkvrSC3S9BCrIQ/0',
                'https://wx.qlogo.cn/mmopen/vi_32/CoHtDnx1Iw8jcsUsibicc3dkOic6TybmQuh0Vq6LAWUzhbYvWibGbR6ybBKRN8PDOTib5svtegBENoSs7tKjGUXiaWnA/0',
                'https://wx.qlogo.cn/mmopen/vi_32/DG5T7nYdwEqAPNL2IjicAia5XpfjXLzLN5ofSfwsGWmnW8B3RKTfkQiaNwWORMxGtxQ9akib51FDzTrJ70AGZxAhSg/0'
            ]
        ];
        return jsonData(1, 'OK', $data);
    }

}