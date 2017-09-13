<?php
namespace app\core\controller;

use think\Request;
use app\core\model\Coupon;
use app\core\model\CouponList;
use app\core\model\Orders;
use app\core\model\Shop;
use app\core\model\User;
use app\core\provider\BotPrinter;

class Test
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
        \app\core\provider\Orders       $p_order,
        \app\core\model\Orders          $m_order,
        \app\core\model\Shop            $m_shop,
        \app\core\model\Coupon          $m_coupon,
        \app\core\model\User            $m_user,
        \app\core\model\CouponList      $m_couponlist
    )
    {
        //验证授权合法
        $p_auth->check($request, [
            'public' => ['*'],
            'private'=> []
        ]);

        //授权服务
        $this->p_auth   = $p_auth;


        //订单服务
        $this->p_order  = $p_order;

        //订单模型
        $this->m_order  = $m_order;

        //商户信息模型
        $this->m_shop   = $m_shop;

        //优惠券模型
        $this->m_coupon = $m_coupon;

        //用户模型
        $this->m_user   = $m_user;

        //用户优惠券模型
        $this->m_couponlist = $m_couponlist;

    }

    public function tib()
    {
//        $session        = $this->p_auth->session();
//        $openid         = $session['openid'];
        $userid         = 1;//$session['userid'];
        $ase         = input('param.');
        $ordersn        = $ase['orders'];//$this->p_order->getOrderSN();
        $post_data = [
            "appid" => "wx3fcef4db43bcfaed",
            "bank_type" => "CFT",
            "cash_fee" => "1",
            "fee_type" => "CNY",
            "is_subscribe" => "N",
            "mch_id" => "1487245952",
            "nonce_str" => "26ymcv8mw9tsl9raak8oe91y885k35w5",
            "openid" => "opkjx0OfG53ZhOpEj-VWqpN_MxR0",
            "out_trade_no" => "2017091355484910",
            "result_code" => "SUCCESS",
            "return_code" => "SUCCESS",
            "sign" => "7C3A51E3E58D0D5D7B90F33A3BF66B27",
            "time_end" => "20170913155211",
            "total_fee" => "1",
            "trade_type" => "JSAPI",
            "transaction_id" => "4009722001201709131868745134"
        ];

        $order_info = [
            "order_sn" => $ordersn,
            "total_price" => 77,
            "is_first" => 1,
            "first_money" => 1,
            "coupon_list_id" => 1,
            "coupon_price" => 5,
            "must_price" => 0,
            "pay_price" => 0,
            "order_money"=> 0,
            "offset_money"=> 52,
            "goods_price"=> 77,
            "goods_list"=> '[{"id":1,"image":"http://img1.my-shop.cc/picture/20170909/dc6824f0c733f321c9ced0d063a6a42b.jpeg","sale":0,"attrs":"[{"titles":"","prices":""}]","price":15,"cat_id":1,"package":1,"rank":1,"name":"腐竹烧肉","num":3,"count_price":45},{"id":2,"image":"http://img1.my-shop.cc/picture/20170909/796818169b22feeaa173867ec4e91508.jpeg","sale":0,"attrs":"[{"titles":"","prices":""}]","price":20,"cat_id":1,"package":1,"rank":2,"name":"台湾卤肉","num":1,"count_price":20},{"id":1,"name":"餐具","price":2,"img_url":"","cate_id":2,"num":5,"is_canju":true,"is_change_item":true,"count_price":10},{"id":2,"name":"纸巾","price":1,"img_url":"","cate_id":2,"num":2,"is_canju":true,"is_change_item":false,"count_price":2}]',
            "pay_way"=> 0,
            "desk_sn"=> "1",
            "message"=> "",
            "user_count"=> 3,
            "mode_money"=> 4.16,
            'user_list' => [],
            'remark' => ''

        ];

        $info = [

            'order_sn'           => $ordersn,                    //是否老订单
            'shop_id'           => $ase['shop'],                             //商户id
            'user_id'           => $ase['user'],                             //顾客id

            'desk_sn'           => $order_info['desk_sn'],                    //桌位编号
            'user_count'        => $order_info['user_count'],                 //就餐人数


            'is_first'          => $order_info['is_first'],                   //首次消费       0 等于首次消费
            'first_money'       => $order_info['first_money'],                //首次立减金额

            'mode_rate'         => 0.08,                                //红包比率
            'mode_money'        => $order_info['must_price'] * 0.08,          //红包金额


            //￥ = goods_price
            'total_price'       => $order_info['total_price'],                //总价
            'coupon_list_id'    => $order_info['coupon_list_id'],             //优惠卷id
            'coupon_price'      => $order_info['coupon_price'],               //优惠金额

            'must_price'        => $order_info['must_price'],                 //应该支付金额
            'pay_price'         => $order_info['pay_price'],                  //实际支付金额

            'order_rate'        => 0.02,                                //手续费比率
            'order_money'       => $order_info['order_money'],                //手续费金额

            'offset_money'      => $order_info['offset_money'],               //使用红包抵扣金额
            'shop_price'        => $order_info['must_price'],                 //商家实际到账金额

            'goods_price'       => $order_info['goods_price'],                //商品总价
            'goods_list'        => $order_info['goods_list'],                 //购物车(商品列表)
            'user_list'         => $order_info['user_list'],                  //同桌用户

            'message'           => $order_info['message'],                    //给商家留言
            'remark'            => $order_info['remark'],                     //口味备注

            'pay_way'           => $order_info['pay_way'],                    //支付方式
            'pay_time'          => 0,                                   //支付完成时间


            'created'           => time(),
            'updated'           => time()
        ];


        //结束订单(事务处理)
        $result = $this->p_order->endOrderStatus($info, $post_data,2);

        print_r($result);
    }

    public function ast(){
        $orders = new Orders();
        $map = input('param.');
        $rew = $orders->error_log($map['orders'],$map['status']);
        print_r($rew);
    }

    public function ada(){

        $data = input('param.');
        $rew = my_log($data['name'],$data['id'],$data['url'],$data['status'],$data['explain']);
        print_r($rew);
    }
}
