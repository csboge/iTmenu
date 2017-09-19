<?php

namespace app\core\controller;

use app\api\controller\Buy;
use app\core\model\RedCashLog;
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

    private $p_auth;

    /***
     * 注入依赖
     *
     */
    public function __construct(
        Request $request,
        \app\core\provider\Auth         $p_auth,
        \app\core\provider\Orders       $p_order,
        \app\core\model\Orders          $m_order,
        \app\core\model\Shop            $m_shop,
        \app\core\model\Coupon          $m_coupon,
        \app\core\model\User            $m_user,
        \app\core\model\CouponList      $m_couponlist,
        \app\core\model\RedCashLog      $m_redcashlog,
        \app\core\model\RedCash         $m_red,
        \app\core\model\Goods           $m_goods,
        \app\core\model\Tistics         $m_tistics
    )
    {
        //验证授权合法
        $p_auth->check($request, [
            'public' => ['*'],
            'private' => []
        ]);

        //授权服务
        $this->p_auth = $p_auth;

        //红包模型
        $this->m_red            = $m_red;

        //订单服务
        $this->p_order = $p_order;

        //订单模型
        $this->m_order = $m_order;

        //商户信息模型
        $this->m_shop = $m_shop;

        //优惠券模型
        $this->m_coupon = $m_coupon;

        //用户模型
        $this->m_user = $m_user;

        //用户优惠券模型
        $this->m_couponlist = $m_couponlist;

        //抢红包模型
        $this->m_redcashlog     = $m_redcashlog;

        //商品模型
        $this->m_goods     = $m_goods;
        $this->m_tistics     = $m_tistics;

    }

    public function ta(){
        $a = input('param.');
        $data = $this->m_order->upTistics($a['orders'],$a['userid'],$a['tistics']);
        print_r($data);exit;

    }

    public function tib()
    {
//        $session        = $this->p_auth->session();
//        $openid         = $session['openid'];
        $userid = 1;//$session['userid'];
        $ase = input('param.');
        $ordersn = $ase['orders'];//$this->p_order->getOrderSN();
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
            "coupon_list_id" => 0,
            "coupon_price" => 5,
            "must_price" => 0,
            "pay_price" => 19,
            "order_money" => 0,
            "offset_money" => 52,
            "goods_price" => 77,
            "desk_sn" => '001',
            "user_count" => 1,
            "user_list" => 0,
            "message" => '',
            "remark" => '',
            "pay_way" => 0,
            "mode_money" => 10,
            "order_rate" => 0.02,
            "goods_list" => '[
                {
                    "id": 1,
                    "image": "http://img1.my-shop.cc/picture/20170909/dc6824f0c733f321c9ced0d063a6a42b.jpeg",
                    "sale": 0,
                    "attrs": "[{\'titles\':\'\',\'prices\':\'\'}]",
                    "price": 15,
                    "cat_id": 1,
                    "package": 1,
                    "rank": 1,
                    "name": "腐竹烧肉",
                    "num": 3,
                    "count_price": 45
                },
                {
                    "id": 2,
                    "image": "http://img1.my-shop.cc/picture/20170909/796818169b22feeaa173867ec4e91508.jpeg",
                    "sale": 0,
                    "attrs": "[{\'titles\':\'\',\'prices\':\'\'}]",
                    "price": 20,
                    "cat_id": 1,
                    "package": 1,
                    "rank": 2,
                    "name": "台湾卤肉",
                    "num": 1,
                    "count_price": 20
                },
                {
                    "id": 1,
                    "name": "餐具",
                    "price": 2,
                    "img_url": "",
                    "cate_id": 2,
                    "num": 5,
                    "is_canju": true,
                    "is_change_item": true,
                    "count_price": 10
                },
                {
                    "id": 2,
                    "name": "纸巾",
                    "price": 1,
                    "img_url": "",
                    "cate_id": 2,
                    "num": 2,
                    "is_canju": true,
                    "is_change_item": false,
                    "count_price": 2
                }
            ]'
        ];

        $info = [

            'order_sn' => $ordersn,                    //是否老订单
            'shop_id' => $ase['shop'],                             //商户id
            'user_id' => $ase['user'],                             //顾客id

            'desk_sn' => $order_info['desk_sn'],                    //桌位编号
            'user_count' => $order_info['user_count'],                 //就餐人数


            'is_first' => $order_info['is_first'],                   //首次消费       0 等于首次消费
            'first_money' => $order_info['first_money'],                //首次立减金额

            'mode_rate' => $order_info['must_price'],                                //红包比率
            'mode_money' => $order_info['mode_money'],          //红包金额


            //￥ = goods_price
            'total_price' => $order_info['total_price'],                //总价
            'coupon_list_id' => $order_info['coupon_list_id'],             //优惠卷id
            'coupon_price' => $order_info['coupon_price'],               //优惠金额

            'must_price' => $order_info['must_price'],                 //应该支付金额
            'pay_price' => $order_info['pay_price'],                  //实际支付金额

            'order_rate' => $order_info['order_rate'],                                //手续费比率
            'order_money' => $order_info['order_money'],                //手续费金额

            'offset_money' => $order_info['offset_money'],               //使用红包抵扣金额
            'shop_price' => $order_info['must_price'],                 //商家实际到账金额

            'goods_price' => $order_info['goods_price'],                //商品总价
            'goods_list' => $order_info['goods_list'],                 //购物车(商品列表)
            'user_list' => $order_info['user_list'],                  //同桌用户

            'message' => $order_info['message'],                    //给商家留言
            'remark' => $order_info['remark'],                     //口味备注

            'pay_way' => $order_info['pay_way'],                    //支付方式
            'pay_time' => 0,                                   //支付完成时间


            'created' => time(),
            'updated' => time()
        ];


//        $printer = new \app\core\provider\BotPrinter();
//
//        $aa = json_encode($info['goods_list'], true);
////        print_r($aa);exit;
//        $printer->printOrderInfo($info, $post_data);
        //结束订单(事务处理)
        $bay = new \app\core\provider\Orders();

        $result = $bay->endOrderStatus($info, $post_data);

        print_r($result);
    }

    public function ast()
    {
        $orders = new RedCashLog();
        $map = input('param.');
        $rew = $orders->error_log($map['orders'], $map['status']);
        print_r($rew);
    }

    public function ada()
    {
        $res = input('param.');

        $data = $this->m_goods->getBowl($res['shop']);
        foreach ($data as &$volue){
            $volue['name'] = $volue['title'];
            $volue['cate_id'] = $volue['cat_id'];
            $volue['image'] = ImgUrl($volue['image']);
            if($volue['bowl'] == 1){
                $volue['num'] = 2;
            }else{
                $volue['num'] = 0;
            }
            unset($volue['title']);
            unset($volue['cat_id']);
        }
        print_r($data);exit;
        foreach ($data as &$itme){
            $vcr = $this->m_user->getUserForId($itme['user_id']);
            $itme['nickname'] = $vcr['nickname'];
            $itme['avatar'] = $vcr['avatar'];
            $itme['sex'] = $vcr['sex'];
        }

        print_r($data);
    }


    public function adsast()
    {
        $ret = 1;
        $user_money = 0;
        $user_coupon = 1;

        if (!$ret || !$user_money || !$user_coupon) {
            echo 333333333333;
        }else{
            echo  22222222222222;
        }
    }

    public function asde(){
        $stat = $this->m_red->endTime(1);
        $time = time();
        $data = ($time-$stat)/(60*60*24);
        $rev = date('Y-m-d H:i:s',$stat);
        $red = date('Y-m-d H:i:s',$time);
        print_r($rev);echo "<br>";
        print_r($data);echo "<br>";

    }


}
