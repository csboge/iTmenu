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
     * 生成 订单号
     *
     * @return  int
     */
    public function getOrderSN()
    {
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }


    

    /**
     * 结束订单（订单号）
     *
     * @param   string   $ordersn    订单号
     * @param   array    $info       业务参数
     *
     * @return  result 
     *
     */
    public function endOrderStatus($ordersn, $info)
    {
        $result     = $this->m_order->getOrderForSN($ordersn);
        if (!$result) return false;

        $endtime    = time();
        $data       = ['status' => 1, 'pay_time' => $endtime, 'updated' => $endtime];

        //支付签名
        $signData          = array_merge($data, $result);
        $data['sign_code'] = $this->getSign($signData);

        //更新订单
        $ret  = $this->m_order->save($data, ['order_sn' => $ordersn]);
        
        return $ret;
    }




    /**
     * 是否是新顾客
     *
     * @param   int     $shopid    商户id
     * @param   int     $userid    顾客id
     * @param   array   $info      数组
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
     * @param   string  $ordersn   订单号
     * @param   int     $shopid    商户id
     * @param   int     $userid    顾客id
     * @param   int     $deskid    桌位id
     * @param   array   $info      数组
     *
     * @return  array
     */
    public function initOrderData($ordersn, $shopid, $userid, $deskid, $info)
    {
        $result  = $this->m_order->getOrderForSN($ordersn);
        if ($result) { return false; }

        $info['is_first'] = !isset($info['is_first']) ? 1 : intval($info['is_first']);
        



        $data    = array(
            'order_sn'          => $order_sn,                           //订单号

            'shop_id'           => $shopid,                             //商户id
            'user_id'           => $userid,                             //顾客id
            'desk_id'           => $deskid,                             //桌位id

            'status'            => 0,

            'is_first'          => $info['is_first'],                   //首次消费      0 等于首次消费

            'mode_rate'         => 0.8,                                 //红包比率
            'mode_money'        => $info['pay_price'] * 0.8,            //红包金额

            //￥ = goods_price
            'total_price'       => $info['total_price'],                //总价
            'coupon_list_id'    => $info['coupon_list_id'],             //优惠卷id     -1等于首次消费
            'coupon_price'      => $info['coupon_price'],               //优惠金额

            'must_price'        => $info['must_price'],                 //应该支付金额
            'pay_price'         => $info['pay_price'],                  //实际支付金额
            
            'order_rate'        => 0.02,                                //手续费比率
            'order_money'       => $info['order_money'] * 0.02,         //手续费金额

            'offset_money'      => $info['offset_money'],               //使用红包抵扣金额


            //￥ =  
            'shop_price'        => $info['shop_price'],                 //商家实际到账金额          

            'goods_price'       => $info['goods_price'],                //商品总价
            'goods_list'        => $info['goods_list'],                 //购物车(商品列表)
            'user_list'         => $info['user_list'],                  //同桌用户

            
            'message'           => $info['message'],                    //给商家留言
            'remark'            => $info['remark'],                     //口味备注

            
            'pay_way'           => $info['pay_way'],                    //支付方式
            'pay_time'          => 0,                                   //支付完成时间

            'created'           => time(),
            'updated'           => time()
        );


        //新增
        $result = $this->m_order->data($data)->save();
        if (!$result) { return false; }


        //返回完整的订单信息
        return $data;
    }


    //作用：生成签名
    private function getSign($obj, $sign = 'csboge_end_order') {

        foreach ($obj as $k => $v) {
            $Parameters[$k] = $v;
        }

        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);

        //签名步骤二：在string后加入KEY
        $String = $String . "&sign=" . $sign;
        
        //签名步骤三：MD5加密
        $String = md5($String);

        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);

        return $result_;
    }


    ///作用：格式化参数，签名过程需要使用
    private function formatBizQueryParaMap($paraMap, $urlencode) {
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