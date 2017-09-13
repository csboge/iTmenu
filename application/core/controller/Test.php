<?php
namespace app\core\controller;

use app\core\model\Coupon;
use app\core\model\CouponList;
use app\core\model\Orders;
use app\core\model\Shop;
use app\core\model\User;
use app\core\provider\BotPrinter;

class Test
{
    public function index()
    {

        $data = new BotPrinter();
        $order_info = 1;
        $post_data = 1;
        $data->printOrderInfo($order_info, $post_data);
        echo  111111;
    }

    public function ast(){
        $orders = new CouponList();
        $map = input('param.');
        $rew = $orders->CouponStatus($map['shopid'],$map['userid']);
        print_r($rew);
    }
}
