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
class Orders
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

        //订单模型
        $this->m_orders      = $m_orders;
    }

    /***
     * 收益 -- 总额
     * @参数 shop_id           商户id
     */
    public function orderList(){
        //获得商店id
        $shop     = $this->p_auth->getShopId();

        $todey = strtotime(date('Y-m-d',time()));
        $small = strtotime('2017-8-20');
        $nodey = ($todey-$small)/(24*60*60);
        for($i = 1;$i >= $nodey;$i++){
            $time[$i] = (24*60*60);
        }
        return $i;
        exit;
//        $start = 1504266077;
//        $end = 1505821277;
//        $data = $this->m_orders->timeOrders($shop,$start,$end);

    }







}