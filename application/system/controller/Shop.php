<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/22
 * Time: 17:55
 */

namespace app\system\controller;

use think\Request;
use think\Db;


class Shop
{
    use \app\core\traits\ProviderFactory;

    private     $p_auth;

    /***
     * 注入依赖
     *
     */
    public function __construct(
        Request                         $request,
        \app\core\provider\Auth         $p_auth,
        \app\core\model\User            $m_user,
        \app\core\model\Shop            $m_shop,
        \app\core\model\Orders          $m_orders
    )
    {
        //验证授权合法
        $p_auth->check($request, [
            'public' => ['*'],
            'private'=> []
        ]);

        //授权服务
        $this->p_auth           = $p_auth;

        //用户模型
        $this->m_user           = $m_user;

        //商铺模型
        $this->m_shop           = $m_shop;

        //订单模型
        $this->m_orders         = $m_orders;
    }


    public function shopInfo(){
        $shop_id     = $this->p_auth->getShopId();
        if(!$shop_id){
            return jsonDataList(0,'未接收到数据',[]);
        }

        $shop = $this->m_shop->getSystemShop($shop_id);

        return jsonDataList(1,'OK',$shop);

    }

    public function updateShop(){

    }


}