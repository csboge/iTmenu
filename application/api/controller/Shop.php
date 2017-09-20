<?php
namespace app\api\controller;

use think\Request;
use think\Db;

class Shop
{
    private     $p_auth;

    /***
     * 公共 - 注入依赖
     *
     */
    public function __construct(
        Request                         $request,
        \app\core\provider\Auth         $p_auth,
        \app\core\provider\WeChat       $p_wechat
    )
    {
        //验证授权合法
        $p_auth->check($request, [
            'public' => ['*'],
            'private'=> []
        ]);

        //授权服务
        $this->p_auth           = $p_auth;

        //微信服务
        $this->p_wechat         = $p_wechat;

    }


    /***
     * 商户 - 信息
     */
    function config()
    {
        //获得商店id
        $shop     = $this->p_auth->getShopId();
        $map = [
            'id' => $shop,
            'status' => 1,
            'hd_status' => 1
        ];
        $data = Db::name('shop')
            ->where($map)
            ->field('title,logo,mobile,tel,video,shop_hours,notice,is_first,adress,lng,Lat,stations,package')
            ->find();
        if($data){
            $data['logo'] = ImgUrl($data['logo']);
            $data['video'] = GET_VIDEO_URL.$data['video'];
            return jsonData(1, 'OK', $data);
        }else{
            return jsonData(0, '未查到到数据', []);
        }
    }

    /***
     * 商户 - 推荐菜品
     */
    function rec(){
        //获得商店id
        $shop  = $this->p_auth->getShopId();

        $map = [
            'shop_id' => $shop,
            'rec' => 1,
            'status' => 1,
            'hd_status' => 1,
            'sd_status' => 1
        ];
        $data = Db::name('goods')
            ->where($map)
            ->order('rank asc')
            ->field('id,title,image,price,intro')
            ->limit(3)
            ->select();

        if($data){
            foreach ($data as &$volue){
                $volue['image'] = ImgUrl($volue['image']);
            }
            return jsonData(1, 'OK', $data);
        }else{
            return jsonData(0, '未查到到数据', []);
        }
    }

    /***
     * 商户 - 优惠券
     */
    function coupon(){
        //用户信息
        $user    = $this->p_auth->session();

        //获得商店id
        $shop     =  $this->p_auth->getShopId();

        $map = [
            'shop_id' => $shop,
            'is_time' => 0,
            'hd_status' => 1,
        ];
        $data = Db::name('coupon')
            ->where($map)
            ->field('id,shop_id,title,type,dis_price,start_time,end_time,conditon,created')
            ->select();

        if($data){
            foreach ($data as $key=>&$volue){
                $volue['created'] = date('Y-m-d H:i:s',$volue['created']);
                $over = over_coupon($volue['id'],$shop);//是否已过期
                if($over){
                    $volue['over'] = 1;
                }else{
                    $volue['over'] = 0;
                }
                $num = num_coupon($volue['id'],$shop);//是否已领完优惠券
                if($num){
                    $volue['biaoshi'] = 1;
                }else {
                    $volue['biaoshi'] = 0;
                }
                $coupon = is_coupon($user['userid'],$volue['id'],$shop);//是否已领取优惠券
                if(!$coupon){
                    $volue['linqu'] = 1;
                }else{
                    $volue['linqu'] = 0;
                }
            }
            return jsonData(1, 'OK', $data);
        }else{
            return jsonData(1, '未查到到数据', []);
        }
    }

    /***
     * 商户 - 优惠券
     */
    public function cou(){

        $shop      =  $this->p_auth->getShopId();//获得商店id

        $bagid     =  $this->p_auth->getBagid();//获得红包id

        $data = GET_IMG_URL.$this->p_wechat->code($shop,$bagid); //获取微信小程序二维码



        header('content-type:image/png');

        $A = GET_IMG_URL."picture/1.jpeg";
        $B = GET_IMG_URL."picture/2.jpg";

        $im1 = imagecreatefromstring(file_get_contents($A));
        $im2 = imagecreatefromstring(file_get_contents($B));

        imagecopymerge($im1, $im2, 110, 390, 0, 0, imagesx($im2), imagesy($im2), 100);

        imagepng($im1);
        imagedestroy($im1);

//        return jsonData(1, 'OK', $data);
    }

}