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
        \app\core\provider\Auth         $p_auth
    )
    {
        //验证授权合法
        $p_auth->check($request, [
            'public' => ['*'],
            'private'=> []
        ]);

        //授权服务
        $this->p_auth   = $p_auth;

    }



    /***
     * 商户 - 信息设置
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
        $data = Db::name('shop')->where($map)->field('id,title,logo,notice,mobile')->find();
        if($data){
            $data['logo'] = ImgUrl($data['logo']);
            return jsonData(1, 'OK', $data);
        }else{
            return jsonData(405, '未查到到数据', null);
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
            return jsonData(405, '未查到到数据', null);
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
                if(!$num){
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
            return jsonData(405, '未查到到数据', null);
        }
    }


}