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
    private $m_user;
    private $m_couponlist;
    private $m_shop;
    private $m_coupon;
    private $request;

    public function __construct()
    {
        //订单模型
        $this->m_order = new \app\core\model\Orders();

        //用户模型
        $this->m_user = new \app\core\model\User();

        //用户优惠券模型
        $this->m_couponlist = new \app\core\model\CouponList();

        //优惠券模型
        $this->m_coupon = new \app\core\model\Coupon();

        //商户模型
        $this->m_shop = new \app\core\model\Shop();

        //获取当前控制器名
        $this->request = \think\Request::instance();

    }


    /**
     * 生成 订单号
     *
     * @return  int
     */
    public function getOrderSN()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }


    /**
     * 结束订单
     *
     * @param   string $ordersn 订单号
     * @param   array $order_info 订单数据
     *
     * @return  result
     *
     */
    public function endOrderStatus($order_info, $post_data, $type = '')
    {
        if (!$order_info || !$post_data)
        {
            return false;
        }

        //当前模块控制器方法
        $action_name= $this->request->module().DS.$this->request->controller().DS.$this->request->action();


        $pay_time = time();
        $data = ['status' => 1, 'pay_time' => $pay_time, 'updated' => $pay_time, 'transaction_id' => $post_data['transaction_id'], 'time_end' => $post_data['time_end']];

        if (!empty($type))
        {
            /**
             * 订单信息验证
             */

            //新用户验证
            $count = $this->m_order->isFirstCons($order_info['shop_id'], $order_info['user_id']);

            if ($order_info['is_first'] > 0 && $count > 0)
            {
                my_log('orders',$order_info['order_sn'],$action_name,-$type,'新用户验证不通过');
                $this->m_order->error_log($order_info['order_sn']);

                return false;
            }

            //首次立减金额验证
            $first_money = $this->m_shop->isShopMoney($order_info['shop_id']);
            if ($order_info['is_first'] > 0 && $order_info['first_money'] !== $first_money)
            {
                my_log('orders',$order_info['order_sn'],$action_name,-$type,'首次立减金额不通过');

                $this->m_order->error_log($order_info['order_sn']);

                return false;
            }

            if ($order_info['coupon_list_id'] !== 0 && $order_info['coupon_price'] !== 0) {
                //优惠券是否存在
                $coupon_price = $this->m_coupon->isCouponPrice($order_info['coupon_list_id']);
                if (!$coupon_price)
                {
                    my_log('orders',$order_info['order_sn'],$action_name,-$type,'优惠券不存在');

                    $this->m_order->error_log($order_info['order_sn']);

                    return false;
                }

                //优惠金额是否正确
                $coupon_money = $this->m_coupon->isCouponMoney($order_info['coupon_list_id']);
                if ($order_info['coupon_price'] != $coupon_money)
                {
                    my_log('orders',$order_info['order_sn'],$action_name,-$type,'优惠金额不对');

                    $this->m_order->error_log($order_info['order_sn']);

                    return false;
                }
            }

            //红包余额
            $is_money = $this->m_user->isMoney($order_info['user_id']);
            if ($is_money < $order_info['offset_money'])
            {
                my_log('orders',$order_info['order_sn'],$action_name,-$type,'红包余额不对');

                $this->m_order->error_log($order_info['order_sn']);

                return false;
            }

        }

        Db::startTrans();

        try {

            //更新订单
            $ret = $this->m_order->save($data, ['order_sn' => $order_info['order_sn'], 'user_id' => $order_info['user_id']]);
            if ($order_info['offset_money'] !== 0) {
                //修改用户钱包余额
                $user_money = $this->m_user->userMoney($order_info['user_id'], $order_info['offset_money']);
            } else {
                $user_money = 1;
            }
            if ($order_info['coupon_list_id'] !== 0) {
                //修改用户优惠券使用记录
                $user_coupon = $this->m_couponlist->CouponStatus($order_info['user_id'], $order_info['coupon_list_id']);
            } else {
                $user_coupon = 1;
            }

            if (!$ret || !$user_money || !$user_coupon) {
//
                // 回滚事务
                Db::rollback();
                //订单错误

                my_log('orders',$order_info['order_sn'],$action_name,-1,'执行出错~~事务回滚1ret:'.$ret.';user_money:'.$user_money.';user_coupon:'.$user_coupon);

                $this->m_order->error_log($order_info['order_sn']);

                return false;

            }
            // 提交事务
            Db::commit();

            return $ret;

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            //订单错误

            my_log('orders',$order_info['order_sn'],$action_name,-1,'执行出错~~事务回滚2');

            $this->m_order->error_log($order_info['order_sn']);

            return false;
        }
    }


    /**
     * 是否是新顾客
     *
     * @param   int $shopid 商户id
     * @param   int $userid 顾客id
     * @param   array $info 数组
     *
     * @return  array   最后一次消费
     */
    public function isFirstOrder($shopid, $userid)
    {

        $row = $this->m_order->where('shop_id', $shopid)
            ->where('user_id', $userid)
            ->where('status', 1)
            ->order('created desc')->limit(1);

        return $row;
    }


    /**
     * 初始化 订单信息
     *
     * @param   string $ordersn 订单号
     * @param   int $shopid 商户id
     * @param   int $userid 顾客id
     * @param   int $desk_sn 桌位编号
     * @param   array $info 数组
     *
     * @return  array
     */
    public function initOrderData($ordersn, $shopid, $userid, $desk_sn, $info)
    {
        //如果有订单号 - 说明是老订单
        if (isset($info['order_sn']) && $info['order_sn']) {
            $result = $this->m_order->getOrderForSN($info['order_sn']);
            if ($result) {
                return $result;
            }
        }

        $data = array(
            'order_sn' => $ordersn,                            //订单号

            'shop_id' => $shopid,                             //商户id
            'user_id' => $userid,                             //顾客id

            'desk_sn' => $desk_sn,                            //桌位编号
            'user_count' => $info['user_count'],                 //就餐人数

            'is_first' => $info['is_first'],                   //首次消费      0 等于首次消费
            'first_money' => $info['first_money'],                //首次立减金额

            'mode_rate' => $info['mode_rate'],                  //红包比率
            'mode_money' => $info['mode_money'],                 //红包金额

            //￥ = goods_price
            'total_price' => $info['total_price'],                //总价
            'coupon_list_id' => $info['coupon_list_id'],             //优惠卷id     -1等于首次消费
            'coupon_price' => $info['coupon_price'],               //优惠金额

            'must_price' => $info['must_price'],                 //应该支付金额
            'pay_price' => $info['pay_price'],                  //实际支付金额

            'order_rate' => $info['order_rate'],                 //手续费比率
            'order_money' => $info['order_money'],                //手续费金额

            'offset_money' => $info['offset_money'],               //使用红包抵扣金额


            //￥ =  
            'shop_price' => $info['shop_price'],                 //商家实际到账金额

            'goods_price' => $info['goods_price'],                //商品总价
            'goods_list' => $info['goods_list'],                 //购物车(商品列表)
            'user_list' => $info['user_list'],                  //同桌用户


            'message' => $info['message'],                    //给商家留言
            'remark' => $info['remark'],                     //口味备注


            'pay_way' => $info['pay_way'],                    //支付方式
            'pay_time' => $info['pay_time'],                  //支付完成时间

            'status' => $info['status'],
            'created' => time(),
            'updated' => time()
        );


        //新增
        $result = $this->m_order->data($data)->save();
        if (!$result) {
            return false;
        }

        //返回完整的订单信息
        return $data;
    }


    //作用：生成签名
    public function getSign($obj, $sign = 'csboge_end_order')
    {

        foreach ($obj as $k => $v) {
            $Parameters[$k] = $v;
        }

        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);

        //签名步骤二：在string后加入KEY
        $String = $String . "&key=" . $sign;

        //签名步骤三：MD5加密
        $String = md5($String);

        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);

        return $result_;
    }


    ///作用：格式化参数，签名过程需要使用
    private function formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }
}