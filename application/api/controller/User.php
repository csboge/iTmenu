<?php
namespace app\api\controller;

use think\Request;
use think\Db;

class User
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
     * 用户 - 红包余额
     * @参数 id           用户id
     */
    function money()
    {
        $id = input('param.id');
        if(empty($id))return jsonData(404, '未接收到数据', null);
        $data = Db::name('user')->where('id',$id)->field('id,money')->find();
        if($data){
            return jsonData(1, 'OK', $data);
        }else{
            return jsonData(405, '未查到到数据', null);
        }
    }


    /***
     * 用户 - 优惠券个数
     * @参数 id           用户id
     * @参数 shop         商户id
     */
    function coupon_display()
    {
        $where = input('param.');
        if(empty($where))return jsonData(404, '未接收到数据', null);
        $map = [
            'user_id' => $where['id'],
            'shop_id' => $where['shop'],
            'status' =>1,
            'u_status' => 0
        ];
        $data = Db::name('coupon_list')->where($map)->count();
        if($data){
            return jsonData(1, 'OK', $data);
        }else{
            return jsonData(405, '未查到到数据', null);
        }
    }


    /***
     * 用户 - 订单记录
     * @参数 id           用户id
     * @参数 shop         商户id
     * @参数 page         页码
     * @参数 limit        条数
     */
    function user_order()
    {
        $where = input('param.');
        if(empty($where))return jsonData(404, '未接收到数据', null);
        $map = [
            'user_id' => $where['id'],
            'shop_id' => $where['shop'],
        ];
        $db = Db::name('orders');
        $page = ($where['page']-1)*$where['limit'];
        $data = $db
            ->where($map)
            ->order('created desc')
            ->field('order_sn,pay_price,status,goods_list')
            ->limit($page,$where['limit'])
            ->select();
        if($data){
            foreach ($data as &$volue){
                if($volue['status'] == 0){
                    $volue['status'] = '待支付';
                }elseif ($volue['status'] == 1){
                    $volue['status'] = '支付成功';
                }elseif ($volue['status'] == 2){
                    $volue['status'] = '交易关闭';
                }
            }
            return jsonData(1, 'OK', $data);
        }else{
            return jsonData(405, '未查到到数据', null);
        }
    }


    /***
     * 用户 - 优惠券列表
     * @参数 id    用户id
     * @参数 shop  商户id
     */
    public function coupon_list(){
        $where = input('param.');
        if(empty($where))return jsonData(404, '未接收到数据', null);
        $map = [
            'user_id' => $where['id'],
            'shop_id' => $where['shop']
        ];
        $page = ($where['page']-1)*$where['limit'];
        $data = Db::name('coupon_list')
            ->where($map)
            ->field('id,sn,coupon_id,u_status')
            ->limit($page,$where['limit'])
            ->select();
        if($data){
            $list_mit = [];
            foreach ($data as &$volue){
                $res = coupon($volue['coupon_id']);
                $volue['effective'] = date('Y-m-d',$res['start_time']).'至'.date('Y-m-d',$res['end_time']);
                if($volue['u_status'] == 0){
                    $volue['u_status'] = '使用';
                    $list_mit['available'][] = $volue;
                }elseif ($volue['u_status'] == 1){
                    $volue['u_status'] = '已使用';
                    $list_mit['short'][] = $volue;
                }elseif ($volue['u_status'] == 2){
                    $volue['u_status'] = '已过期';
                    $list_mit['overdue'][] = $volue;
                }
            }
            return jsonData(1, 'OK', $list_mit);
        }else{
            return jsonData(405, '未查到到数据', null);
        }
    }

    /***
     * 用户 - 红包收入
     * @参数 id    用户id
     */
    public function cash_log(){
        $where = input('param.');
        if(empty($where))return jsonData(404, '未接收到数据', null);
        $map = [
            'user_id' => $where['id']
        ];
        $data = Db::name('red_cash_log')
            ->where($map)
            ->order('created desc')
            ->field('red_cash_id,audio,words,menoy,created,shop_id')
            ->select();
        if($data){
            foreach ($data as &$volue){
                $res = shop_title($volue['shop_id']);
                $volue['title'] = $res['title'];
                $volue['logo'] = ImgUrl($res['logo']);
            }
            return jsonData(1, 'OK', $data);
        }else{
            return jsonData(405, '未查到到数据', null);
        }
    }

    /***
     * 用户 - 领取优惠券
     * @参数 user_id    用户id
     * @参数 coupon_id  优惠券id
     * @参数 shop_id    店铺id
     */
    public function get_coupon(){
        $where = input('param.');
        if(empty($where))return jsonData(404, '未接收到数据', null);
        //判断是否可以领取
        $is = is_coupon($where['user_id'],$where['coupon_id'],$where['shop_id']);
        if($is)return jsonData(306, '已领过', null);
        //判断优惠券是否已领完
        $num = num_coupon($where['coupon_id'],$where['shop_id']);
        if(!$num)return jsonData(307, '已领完', null);
        // 启动事务
        Db::startTrans();
        try{
            $data = [
                'shop_id' => $where['shop_id'],
                'coupon_id' => $where['coupon_id'],
                'user_id' => $where['user_id'],
                'status' => 1,
                'created' => time(),
                'get_time' => time()
            ];
            Db::name('coupon_list')->insert($data);
            Db::name('coupon')->where(['id' => $where['coupon_id']])->setInc('get_num');
            // 提交事务
            Db::commit();
            return jsonData(1, 'OK', null);
        }catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return jsonData(405, '领取失败', null);
        }
    }

    /***
     * 用户 - 红包收入
     * @参数 user_id    用户id
     * @参数 page       页数
     * @参数 limit      条数
     */
    public function income(){
        $where = input('param.');
        if(empty($where))return jsonData(404, '未接收到数据', null);
        $map =[
            'user_id' => $where['user_id']
        ];
        $db = Db::name('red_cash_log');
        $page = ($where['page']-1)*$where['limit'];
        $res = $db
            ->where($map)
            ->order('created desc')
            ->field('id,audio,words,menoy,shop_id,created')
            ->limit($page,$where['limit'])
            ->select();
        if($res){
            foreach ($res as &$volue){
                $shop = shop_title($volue['shop_id']);
                $volue['audio'] = GET_IMG_URL.$volue['audio'];
                $volue['logo'] = ImgUrl($shop['logo']);
                $volue['title'] = $shop['title'];
                $volue['created'] = date('Y-m-d H:i:s',$volue['created']);
                unset($volue['shop_id']);
            }
            return jsonData(1, 'OK', $res);
        }else{
            return jsonData(405, '未查到到数据', null);
        }
    }

    /***
     * 用户 - 红包支出
     * @参数 user_id    用户id
     * @参数 page       页数
     * @参数 limit      条数
     */
    public function expenditure(){
        $where = input('param.');
        if(empty($where))return jsonData(404, '未接收到数据', null);
        $map =[
            'user_id' => $where['id'],
            'status' => 1,
            'pay_time' => ['>',0],
            'offset_money' => ['>',0]
        ];
        $db = Db::name('orders');
        $page = ($where['page']-1)*$where['limit'];
        $res = $db
            ->where($map)
            ->order('pay_time desc')
            ->field('order_sn,shop_id,pay_time,offset_money')
            ->limit($page,$where['limit'])
            ->select();
        if($res){
            foreach ($res as &$volue){
                $shop = shop_title($volue['shop_id']);
                $volue['logo'] = ImgUrl($shop['logo']);
                $volue['title'] = $shop['title'];
                $volue['pay_time'] = date('Y-m-d H:i:s',$volue['pay_time']);
                unset($volue['shop_id']);
            }
            return jsonData(1, 'OK', $res);
        }else{
            return jsonData(405, '未查到到数据', null);
        }
    }
}