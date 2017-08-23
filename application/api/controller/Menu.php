<?php
namespace app\api\controller;

use think\Request;

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
     * 获得 - 菜谱分类
     */
    function category()
    {

        $cate_list = [];
        $cate_list[] = ['id'=>1, 'name'=>'新推套餐', 'list'=>[
            ['id'=>101, 'name'=>'A套餐 168'],
            ['id'=>102, 'name'=>'A套餐 268']
        ]];

        $cate_list[] = ['id'=>2, 'name'=>'炭火烧肉饭(招牌)', 'list'=>[]];
        $cate_list[] = ['id'=>3, 'name'=>'炭烧牛肉饭', 'list'=>[]];
        $cate_list[] = ['id'=>4, 'name'=>'炭烧培根饭', 'list'=>[]];
        $cate_list[] = ['id'=>5, 'name'=>'炭烧猪扒饭', 'list'=>[]];
        $cate_list[] = ['id'=>6, 'name'=>'炭烧里脊肉饭', 'list'=>[]];
        $cate_list[] = ['id'=>7, 'name'=>'时尚小食', 'list'=>[]];
        $cate_list[] = ['id'=>8, 'name'=>'汤品', 'list'=>[]];
        $cate_list[] = ['id'=>9, 'name'=>'活动餐品', 'list'=>[]];
        $cate_list[] = ['id'=>10, 'name'=>'米饭', 'list'=>[]];
        $cate_list[] = ['id'=>11, 'name'=>'饮品', 'list'=>[]];

        
        return jsonData(1, 'ok', ['cate_list'=>$cate_list]);
    }


    /***
     * 获得 - 菜品列表
     */
    function goods()
    {
        
        $goods_list = [];
        $goods_list[] = ['id'=>1, 'name'=>'腐竹烧肉', 'img_url'=>'http://api.ai-life.me/Uploads/goods/new1.jpg', 'price'=>28.00, 'stars'=>5, 'cate_id'=>2];
        $goods_list[] = ['id'=>2, 'name'=>'台湾卤肉', 'img_url'=>'http://api.ai-life.me/Uploads/goods/new1.jpg', 'price'=>28.00, 'stars'=>5, 'cate_id'=>2];
        $goods_list[] = ['id'=>3, 'name'=>'梅菜扣肉', 'img_url'=>'http://api.ai-life.me/Uploads/goods/new1.jpg', 'price'=>28.00, 'stars'=>5, 'cate_id'=>2];
        $goods_list[] = ['id'=>4, 'name'=>'红烧排骨', 'img_url'=>'http://api.ai-life.me/Uploads/goods/new1.jpg', 'price'=>28.00, 'stars'=>5, 'cate_id'=>2];
        $goods_list[] = ['id'=>5, 'name'=>'土豆牛肉', 'img_url'=>'http://api.ai-life.me/Uploads/goods/new1.jpg', 'price'=>28.00, 'stars'=>5, 'cate_id'=>2];
        $goods_list[] = ['id'=>6, 'name'=>'炭火烧肉饭(烧烤味)', 'img_url'=>'http://api.ai-life.me/Uploads/goods/new1.jpg', 'price'=>28.00, 'stars'=>5, 'cate_id'=>3];
        $goods_list[] = ['id'=>7, 'name'=>'炭火烧肉饭(番茄味)', 'img_url'=>'http://api.ai-life.me/Uploads/goods/new1.jpg', 'price'=>28.00, 'stars'=>5, 'cate_id'=>3];
        $goods_list[] = ['id'=>8, 'name'=>'炭火烧肉饭(黑胡椒)', 'img_url'=>'http://api.ai-life.me/Uploads/goods/new1.jpg', 'price'=>28.00, 'stars'=>5, 'cate_id'=>4];
        $goods_list[] = ['id'=>9, 'name'=>'炭火烧肉饭(甜辣味)', 'img_url'=>'http://api.ai-life.me/Uploads/goods/new1.jpg', 'price'=>28.00, 'stars'=>5, 'cate_id'=>5];
        $goods_list[] = ['id'=>10, 'name'=>'炭烧牛肉饭(甜辣味)', 'img_url'=>'http://api.ai-life.me/Uploads/goods/new1.jpg', 'price'=>28.00, 'stars'=>5, 'cate_id'=>5];
        $goods_list[] = ['id'=>11, 'name'=>'炭烧牛肉饭(黑椒味)', 'img_url'=>'http://api.ai-life.me/Uploads/goods/new1.jpg', 'price'=>28.00, 'stars'=>5, 'cate_id'=>6];
        $goods_list[] = ['id'=>12, 'name'=>'炭烧牛肉饭(番茄味)', 'img_url'=>'http://api.ai-life.me/Uploads/goods/new1.jpg', 'price'=>28.00, 'stars'=>5, 'cate_id'=>6];
        $goods_list[] = ['id'=>13, 'name'=>'炭烧牛肉饭(烧烤味)', 'img_url'=>'http://api.ai-life.me/Uploads/goods/new1.jpg', 'price'=>28.00, 'stars'=>5, 'cate_id'=>7];
        $goods_list[] = ['id'=>14, 'name'=>'炭烧培根饭(甜辣味)', 'img_url'=>'http://api.ai-life.me/Uploads/goods/new1.jpg', 'price'=>28.00, 'stars'=>5, 'cate_id'=>7];



        $goods_list[] = ['id'=>15, 'name'=>'绿豆粥', 'img_url'=>'http://api.ai-life.me/Uploads/goods/tang5.jpg', 'price'=>28.00, 'stars'=>5, 'cate_id'=>8];
        $goods_list[] = ['id'=>16, 'name'=>'红枣银耳羹', 'img_url'=>'http://api.ai-life.me/Uploads/goods/tang6.jpg', 'price'=>28.00, 'stars'=>5, 'cate_id'=>8];
        $goods_list[] = ['id'=>17, 'name'=>'皮蛋粥', 'img_url'=>'http://api.ai-life.me/Uploads/goods/tang7.jpg', 'price'=>28.00, 'stars'=>5, 'cate_id'=>8];
        $goods_list[] = ['id'=>18, 'name'=>'红豆粥', 'img_url'=>'http://api.ai-life.me/Uploads/goods/tang8.jpg', 'price'=>28.00, 'stars'=>5, 'cate_id'=>8];


        return jsonData(1, 'ok', ['goods_list'=>$goods_list]);
    }


}