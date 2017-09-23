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
        \app\core\model\Orders          $m_orders,
        \app\core\model\Shop            $m_shop,
        \app\core\model\Tistics         $m_tistics
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

        //商户模型
        $this->m_shup           = $m_shop;

        //订单模型
        $this->m_orders         = $m_orders;

        //入账统计模型
        $this->m_tistics        = $m_tistics;
    }

    /***
     * 收益 -- 总额
     * @参数 shop_id           商户id
     */
    public function orderList(){
        //获得商店id
        $shop     = $this->p_auth->getShopId();
        if(!$shop)return ajaxSuccess(0,'未收到数据',[]);
        $tistics = $this->m_tistics->listTisticsOr($shop);                                        //获取商户收入统计
        if($tistics){
            foreach ($tistics as $key=>&$volue){
                $volue['list'] = $this->m_orders->liseOrder($volue['id'],$shop);                //查询商户入账下的订单
                foreach ($volue['list'] as &$imtil){
                    $imtil['pay_time'] = date('Y-m-d H:i:s',$imtil['pay_time']);
                    $user = $this->m_user->getUserForId($imtil['user_id']);                     //查询用户
                    $imtil['nickname'] = $user['nickname'];
                    $imtil['avatar'] = $user['avatar'];
                    if($user['sex'] == 1){
                        $imtil['sex'] = '男';
                    }elseif ($user['sex'] == 2){
                        $imtil['sex'] = '女';
                    }else{
                        $imtil['sex'] = '保密';
                    }
                }
            }
            return ajaxSuccess(1,'收入统计',$tistics);
        }else{
            return ajaxSuccess(1,'没有收入',null);
        }
    }


    /***
     * 收益 -- 当日
     * @参数 shop_id           商户id
     */
    public function todey(){
        //获得商店id
        $shop     = $this->p_auth->getShopId();
        if(!$shop)return ajaxSuccess(0,'未收到数据',[]);
        $tistics = $this->m_tistics->toeles($shop);                                 //店铺收入
        $title = $this->m_shup->getShop($shop)['title'];                 //店名
        $people = $this->m_orders->ordersTistics($tistics['id']);        //付款人数，收款笔数
        $arr = [
            'id'            =>  $tistics['id'],
            'money'         =>  $tistics['money'],
            'statistics'    =>  $tistics['statistics'],
            'title'         =>  $title,
            'people'        =>  $people
        ];
        return ajaxSuccess(1,'当日收益',$arr);
    }

    /***
     * 收益 -- 日报
     * @参数 shop_id           商户id
     */
    public function deyList(){
        //获得商店id
        $shop       = $this->p_auth->getShopId();
        $page       = input('param.page/d');
        if(!$shop)return ajaxSuccess(0,'未收到数据',[]);
        $tistics = $this->m_tistics->listTistics($shop,$page);                    //店铺收入
        foreach ($tistics as &$time){
            $time['people'] = $this->m_orders->ordersTistics($time['id']);        //付款人数，收款笔数
            $time['single'] = round($time['money']/$time['people'],2);
        }
        return ajaxSuccess(1,'日收益列表',$tistics);
    }
}