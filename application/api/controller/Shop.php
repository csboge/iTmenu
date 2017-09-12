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
     * @参数 title    商户id
     */
    function config()
    {
        $title     = input('param.title/s');
        if(empty($title))return jsonData(404, '未接收到数据', null);
        $map = [
            'id' => $title,
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
     * @参数 shop    商户id
     */
    function rec(){
        $where = input('param.shop');
        if(empty($where))return jsonData(404, '未接收到数据', null);
        $map = [
            'shop_id' => $where,
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
     * @参数 shop    商户id
     */
    function coupon(){
        $where = input('param.');
        if(empty($where))return jsonData(404, '未接收到数据', null);

        //用户信息
        $user    = $this->p_auth->session();

        $map = [
            'shop_id' => $where['shop'],
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
                $num = num_coupon($volue['id'],$volue['shop_id']);
                if(!$num){
                    $volue['biaoshi'] = 0;
                }else {
                    $volue['biaoshi'] = 1;
                }
                $coupon = is_coupon($user['userid'],$volue['id'],$where['shop']);
                if(!$coupon){
                    $volue['linqu'] = 0;
                }else{
                    $volue['linqu'] = 1;
                }
            }
            return jsonData(1, 'OK', $data);
        }else{
            return jsonData(405, '未查到到数据', null);
        }
    }


}