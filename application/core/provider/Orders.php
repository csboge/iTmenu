<?php
namespace app\core\provider;

use think\Db;

/**
 * 订单方面 * 服务 * 操作方法
 *
 *
 *
 */
class Orders
{
    private $m_order;

    public function __construct()
    {
        //用户模型
        $this->m_order = new \app\core\model\Orders();
    }



    /**
     * 初始化 订单信息
     *
     * @param   int     $shopid    商户id
     * @param   int     $userid    顾客id
     * @param   int     $deskid    桌位id
     * @param   array   $info      数组
     *
     * @return  array
     */
    public function initUserData($shopid, $userid, $deskid, $info)
    {

        $order_sn = '';
        $data    = array(
            'order_sn'          => $order_sn,

            'shop_id'           => $shopid,
            'user_id'           => $userid,
            'desk_id'           => $deskid,

            'status'            => 0,

            'is_first'          => 0,           //首次消费      1 等于首次消费

            'mode_rate'         => 0,           //红包比率
            'mode_money'        => 0,           //红包金额

            //￥ = goods_price
            'total_price'       => 0,           //总价
            'coupon_list_id'    => 0,           //优惠卷id     -1等于首次消费
            'coupon_price'      => 0,           //优惠金额

            'must_price'        => 0,           //应该支付金额
            'pay_price'         => 0,           //实际支付金额
            
            'order_rate'        => 0,           //手续费比率
            'order_money'       => 0,           //手续费金额

            'offset_money'      => 0,           //使用红包抵扣金额


            //￥ =  
            'shop_price'        => 0,           //商家实际到账金额          

            'goods_price'       => 0,           //商品总价
            'goods_list'        => '',          //购物车(商品列表)
            'user_list'         => '',          //同桌用户

            
            'message'           => '',          //给商家留言
            'remark'            => '',          //口味备注

            
            'pay_way'           => 1,           //支付方式
            'pay_time'          => 0,           //支付完成时间

            'created'           => time(),
            'updated'           => time()
        );

        //查询用户
        $result  = $this->m_user->getUserForUnionid($unionid);

        //修改
        if ($result) {
            unset($data['created']);

            //记录 *登录次数
            $data['logcount'] = $result['logcount'] + 1;

            $this->m_user->save($data, ['id' => $result['id']]);
            return $result['id'];
        }

        //新增
        $this->m_user->data($data)->save();
        return $this->m_user->id;
    }


}