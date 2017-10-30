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
        \app\core\model\Tistics         $m_tistics,
        \app\core\provider\WeChat       $p_wechat
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
        $this->m_red    = $m_red;

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
        $this->p_wechat     = $p_wechat;

    }

    public function ee(){
        $printer = new \app\core\provider\BotPrinter();

//        $aa = json_encode($info['goods_list'], true);
////        print_r($aa);exit;
//        $printer->printOrderInfo($info, $post_data);
        $printer->getWords_one();
        //结束订单(事务处理)
//        $bay = new \app\core\provider\Orders();

//        $result = $bay->endOrderStatus($info, $post_data);
    }

    public function asc(){
        $imgpath="https://ss0.bdstatic.com/94oJfD_bAAcT8t7mm9GUKT-xh_/timg?image&quality=100&size=b4000_4000&sec=1483602960&di=f93d78756010023602ecb700b802658f&src=http://pic.90sjimg.com/back_pic/u/00/38/54/05/560412efec2d6.jpg";
        $img = GrabImage($imgpath,"D://img");//(图片地址,存放目录,存放显示文件名称)
        print_r($img);
    }

    public function asddsa(){
        $ase = input('param.');
        $first_money = $this->p_wechat->code($ase['shop_id'],$ase['bagid']);
        print_r($first_money);
    }

    public function aased(){
        $appid = 'wx3fcef4db43bcfaed';
        $SECRET = '1354bdaf7a9e13b5fe97c22de97b90b3';
        $token_access_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $SECRET;
        $res = file_get_contents($token_access_url); //获取文件内容或获取网络请求的内容
        //echo $res;
        $result = json_decode($res, true); //接受一个 JSON 格式的字符串并且把它转换为 PHP 变量
        $access_token = $result['access_token'];
        echo $access_token;
    }

    public function ta(){
        $access_token = 'EUmkPotuDteHFu-_lNdFfgEeIhE0L76qbGPZnhHVoPGEy_x0roJ_VHu_lR67Ps1uOzLbWw5Mx7xVsUK7QNwxXzG5HJ_MWc6YksmJCBuEpYMKEWgAAAPJB';
        $path = "https://demo.ai-life.me";
        $width = 430;
        $shop_id = "shop_id=3";

        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=$access_token";

        $post_data = '{"path":"https://demo.ai-life.me","width":430,"scene":"shop_id=3"}';

//        echo $post_data;exit;

        $result=$this->api_notice_increment($url,$post_data);

//        $data = upload_video($result);
//        $chunk_size = (integer)hexdec(fgets( $socket_fd, 4096 ) );
//        while(!feof($socket_fd) && $chunk_size > 0)
//        {
//            $bodyContent .= fread( $socket_fd, $chunk_size );
//            fread( $socket_fd, 2 ); // skip /r/n
//            $chunk_size = (integer)hexdec(fgets( $socket_fd, 4096 ) );
//        }

//        $filepath = ROOT_PATH . 'Uploads/picture/' . date('Ymd', time()) . '/';

//        $id = base64_img($result,'jpeg');
//        $filepath = ImgUrl($id);
//        file_put_contents($filepath, $result);

//        echo "<img src='{$result}' />";

//        $string = gzdecode ($result);
        //return $result;

        //$content=addslashes($result);
        header('Content-type: image/jpg');
        echo $result;
    }

    function api_notice_increment($url, $data){
        $ch = curl_init();
        $header = array("Accept-Charset: utf-8");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        //     var_dump($tmpInfo);
        //    exit;
        if (curl_errno($ch)) {
            return false;
        }else{
            // var_dump($tmpInfo);
            return $tmpInfo;
        }
    }

    public function tib()
    {
//        $session        = $this->p_auth->session();
//        $openid         = $session['openid'];
        $userid = 1;//$session['userid'];
        $ase = input('param.');
//        $ordersn = $ase['orders'];//$this->p_order->getOrderSN();
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
            "total_price"=> 906,
            "is_first"=> 0,
            "first_money"=> 0,
            "coupon_list_id"=> 0,
            "coupon_price"=> 0,
            "must_price"=> 906,
            "pay_price"=> 924.12,
            "order_money"=> 18.12,
            "offset_money"=> 0,
            "goods_price"=> 906,
            "goods_list"=> '[
                {
                    "id": 152,
                    "image": "http://img1.my-shop.cc/picture/20170921/4cec817f638a01be4f49cfb8bcb24147.jpeg",
                    "sale": 0,
                    "attrs": {
                        "titles": "大份",
                        "prices": "150",
                        "is_checked": true,
                        "num": 1
                    },
                    "price": "150",
                    "cat_id": 27,
                    "package": 0,
                    "rank": 0,
                    "bowl": 0,
                    "type_id": 2,
                    "name": "巴拿马花蝴蝶(大份)",
                    "num": 1,
                    "spec_num": 0,
                    "count_price": 150,
                    "is_canju": 0
                },
                {
                    "id": 152,
                    "image": "http://img1.my-shop.cc/picture/20170921/4cec817f638a01be4f49cfb8bcb24147.jpeg",
                    "sale": 0,
                    "attrs": {
                        "titles": "中份",
                        "prices": "138",
                        "is_checked": true,
                        "num": 2
                    },
                    "price": "138",
                    "cat_id": 27,
                    "package": 0,
                    "rank": 0,
                    "bowl": 0,
                    "type_id": 2,
                    "name": "巴拿马花蝴蝶(中份)",
                    "num": 2,
                    "spec_num": 0,
                    "count_price": 276,
                    "is_canju": 0
                },
                {
                    "id": 153,
                    "image": "http://img1.my-shop.cc/picture/20170921/f196170bb0e2db9930dba49009b97bd4.jpeg",
                    "sale": 0,
                    "attrs": [],
                    "price": 98,
                    "cat_id": 27,
                    "package": 0,
                    "rank": 0,
                    "bowl": 0,
                    "type_id": 2,
                    "name": "巴西咖啡",
                    "num": 1,
                    "count_price": 98,
                    "is_canju": 0
                },
                {
                    "id": 343,
                    "image": "http://img1.my-shop.cc/picture/20170923/e623c054c038c2cb7c146359ae8b7ecc.jpeg",
                    "sale": 0,
                    "attrs": [],
                    "price": 48,
                    "cat_id": 53,
                    "package": 0,
                    "rank": 0,
                    "bowl": 0,
                    "type_id": 1,
                    "name": "红烧排骨煲仔饭",
                    "num": 1,
                    "count_price": 48,
                    "is_canju": 0
                },
                {
                    "id": 388,
                    "image": "http://img1.my-shop.cc/picture/20170927/5067d251ed2b8ea748be2d7243358afd.jpeg",
                    "sale": 0,
                    "attrs": [],
                    "price": 28,
                    "cat_id": 61,
                    "package": 0,
                    "rank": 0,
                    "bowl": 0,
                    "type_id": 1,
                    "name": "红糖发糕",
                    "num": 2,
                    "count_price": 56,
                    "is_canju": 0
                },
                {
                    "id": 418,
                    "price": 5,
                    "bowl": 1,
                    "name": "西餐餐具",
                    "cate_id": 64,
                    "img_url": "http://img1.my-shop.cc/picture/20171018/690494fdc0b9e776bf046e155736fdb1.jpeg",
                    "num": 1,
                    "is_canju": true,
                    "is_change_item": true,
                    "count_price": 5
                }
            ]',
            "order_rate"=> 0.02,
            "mode_rate"=> 0.08,
            "order_sn"=> "2017101749509850",
            "pay_way"=> 0,
            "desk_sn"=> "1",
            "message"=> "这个点真好吃",
            "remark"=> "不吃辣; 少放辣",
            "user_count"=> 3,
            "mode_money"=> 73
        ];

        $info = [

            'order_sn' => $order_info['order_sn'],                    //是否老订单
            'shop_id' => $ase['shop'],                             //商户id
            'user_id' => 1,                             //顾客id

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
//            'user_list' => $order_info['user_list'],                  //同桌用户

            'message' => $order_info['message'],                    //给商家留言
            'remark' => $order_info['remark'],                     //口味备注

            'pay_way' => $order_info['pay_way'],                    //支付方式
            'pay_time' => 0,                                   //支付完成时间


            'created' => time(),
            'updated' => time()
        ];

        $printer = new \app\core\provider\BotPrinter();

//        $aa = json_encode($info['goods_list'], true);
////        print_r($aa);exit;
//        $printer->printOrderInfo($info, $post_data);
        $printer->getWords_one();
        //结束订单(事务处理)
//        $bay = new \app\core\provider\Orders();

//        $result = $bay->endOrderStatus($info, $post_data);

        print_r($printer);
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
