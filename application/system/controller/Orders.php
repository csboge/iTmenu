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
        \app\core\model\UserAdmin       $m_useradmin
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

        //管理员模型
        $this->m_useradmin      = $m_useradmin;
    }

    /***
     * 收益 -- 总额
     * @参数 shop_id           商户id
     */
    public function orderList(){
        //获得商店id
        $shop     = $this->p_auth->getShopId();
        $map = [
            'shop_id' => $shop,
            'status' => 1
        ];
        $money = Db::name('orders')->where($map)->sum('shop_price');

        return ajaxSuccess(1,'本店全部收益',$money);
    }







}