<?php
namespace app\core\controller;

use app\api\home\controller\Member;
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
}
