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
class User
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
        \app\core\model\UserAdmin       $m_useradmin,
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

        //管理员模型
        $this->m_useradmin      = $m_useradmin;

        //订单模型
        $this->m_orders         = $m_orders;
    }

    /***
     * 验证 -- 商家管理员
     * @参数 mobile           用户手机号
     * @参数 password         密码
     */
    public function isAdmin(){
        $data = input('param.');
        if(empty($data)){
            return ajaxSuccess(0,'未接收到数据',[]);
        }
        $admin = $this->m_useradmin->isUserAdmin($data['mobile']);
        if(empty($admin)){
            return ajaxSuccess(0,'您不是管理员',[]);
        }
        $admin = $admin->toArray();
        if($admin['status'] == 0){
            return ajaxSuccess(0,'您的授权已过期',[]);
        }
        $rand = $admin['rand'];
        $data['password'] = tplus_ucenter_md5($data['password'],$rand);//加密
        if($admin['password'] !== $data['password']){
            return ajaxSuccess(0,'密码错误',[]);
        }
        $res = [
            'shop_id' => $admin['shop_id'],
            'user_id' => $admin['user_id']
        ];
        return ajaxSuccess(1,'登录成功',$res);
    }

    /***
     * 收益 -- 总额
     * @参数 shop_id           商户id
     */
    public function totalMoney(){
        //获得商店id
        $shop     = $this->p_auth->getShopId();
        if(empty($shop)){
            return ajaxSuccess(0,'未接收到数据',[]);
        }

        $money = $this->m_orders->ordersMoney($shop);           //店铺总收益

        $people = $this->m_orders->ordersPeople($shop);         //付款人数，收款笔数

        $single = round($money/$people, 2);         //单笔均价

        $first = count($this->m_orders->isFirst($shop));        //新用户人数

        $old = count($this->m_orders->isOld($shop));            //老用户人数

        $arr = [
            'money'     => $money,
            'people'    => $people,
            'pens'      => $people,
            'single'    => $single,
            'first'     => $first,
            'old'       => $old
        ];

        return ajaxSuccess(1,'报表详情',$arr);
    }







}