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
        \app\core\provider\Auth         $p_auth,
        \app\core\model\RedCash         $m_red
    )
    {
        //验证授权合法
        $p_auth->check($request, [
            'public' => ['*'],
            'private'=> []
        ]);

        //授权服务
        $this->p_auth   = $p_auth;

        //红包模型
        $this->m_red    = $m_red;

    }

    /***
     * 用户 - 红包余额
     */
    function money()
    {
        //用户信息
        $user    = $this->p_auth->session();

        $data = Db::name('user')->where('id',$user['userid'])->field('id,money')->find();
        if($data){
            return jsonData(1, 'OK', $data);
        }else{
            return jsonData(1, '未查到到数据', null);
        }
    }


    /***
     * 用户 - 优惠券个数
     */
    function coupon_display()
    {
        //用户信息
        $user    = $this->p_auth->session();

        //获得商店id
        $shop     = $this->p_auth->getShopId();

        $map = [
            'user_id' => $user['userid'],
            'shop_id' => $shop,
            'status' =>1,
            'u_status' => 0
        ];
        $data = Db::name('coupon_list')->where($map)->count();
        if($data){
            return jsonData(1, 'OK', $data);
        }else{
            return jsonData(1, '未查到到数据', null);
        }
    }


    /***
     * 用户 - 订单记录
     * @参数 page         页码
     * @参数 limit        条数
     */
    function user_order()
    {
        //用户信息
        $user    = $this->p_auth->session();

        //获得商店id
        $shop     = $this->p_auth->getShopId();

        $where = input('param.');
        if(empty($where))return jsonData(404, '未接收到数据', null);
        $map = [
            'user_id' => $user['userid'],
            'shop_id' => $shop,
            'status' => array('>', '-1')
        ];
        $db = Db::name('orders');
        $limit = $where['limit']?$where['limit']:10;
        $page = ($where['page']-1)*$limit;
        $data = $db
            ->where($map)
            ->order('status asc')
            ->order('created desc')
            ->field('order_sn,pay_price,status,goods_list')
            ->limit($page,$limit)
            ->select();
        if($data){
            foreach ($data as &$volue){
                $volue['bagid'] = $this->m_red->isOrder($volue['order_sn'],$shop);
                if($volue['status'] == 0){
                    $volue['status'] = '待支付';
                }elseif ($volue['status'] == 1){
                    $volue['status'] = '支付成功';
                }elseif ($volue['status'] == 2){
                    $volue['status'] = '交易关闭';
                }elseif($volue['status'] < 0){
                    $volue['status'] = '订单异常';
                }
            }
            return jsonData(1, 'OK', $data);
        }else{
            return jsonData(1, '未查到到数据', null);
        }
    }


    /***
     * 用户 - 优惠券列表
     */
    public function coupon_list(){
        //用户信息
        $user     = $this->p_auth->session();

        //获得商店id
        $shop     = $this->p_auth->getShopId();

        $map = [
            'user_id' => $user['userid'],
            'shop_id' => $shop
        ];
        $data = Db::name('coupon_list')
            ->where($map)
            ->field('id,sn,coupon_id,u_status')
            ->select();
        if($data){
            $list_mit = [];
            foreach ($data as &$volue){
                $res = coupon($volue['coupon_id']);
                $volue['title'] = $res['title'];
                $volue['effective'] = date('Y-m-d',$res['start_time']).'至'.date('Y-m-d',$res['end_time']);
                if($volue['u_status'] == 0)
                {
                    $volue['u_status'] = '0';
                    $list_mit['available'][] = $volue;
                }
                elseif ($volue['u_status'] == 1)
                {
                    $volue['u_status'] = '1';
                    $list_mit['short'][] = $volue;
                }
                elseif ($volue['u_status'] == 2)
                {
                    $volue['u_status'] = '2';
                    $list_mit['overdue'][] = $volue;
                }
            }
            return jsonData(1, 'OK', $list_mit);
        }else{
            return jsonData(1, '未查到到数据', null);
        }
    }


    /***
     * 用户 - 领取优惠券
     * @参数 coupon_id  优惠券id
     */
    public function get_coupon(){
        $where = input('param.');
        if(empty($where))return jsonData(404, '未接收到数据', null);

        //用户信息
        $user    = $this->p_auth->session();

        //获得商店id
        $shop     = $this->p_auth->getShopId();

        //判断是否可以领取
        $is = is_coupon($user['userid'],$where['coupon_id'],$shop);
        if($is)return jsonData(306, '已领过', null);

        //判断优惠券是否已领完
        $num = num_coupon($where['coupon_id'],$shop);
        if(!$num)return jsonData(307, '已领完', null);

        // 启动事务
        Db::startTrans();
        try{
            $data = [
                'shop_id' => $shop,
                'coupon_id' => $where['coupon_id'],
                'user_id' => $user['userid'],
                'status' => 1,
                'created' => time(),
                'get_time' => time()
            ];
            $res1 = Db::name('coupon_list')->insert($data);
            $res2 = Db::name('coupon')->where(['id' => $where['coupon_id']])->setInc('get_num');
            if($res1 && $res2){
                // 提交事务
                Db::commit();
                return jsonData(1, 'OK', null);
            }else{
                // 回滚事务
                Db::rollback();
            }
        }catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return jsonData(405, '领取失败', null);
        }
    }

    /***
     * 用户 - 红包收入
     * @参数 page       页数
     * @参数 limit      条数
     */
    public function income(){
        //用户信息
        $user    = $this->p_auth->session();

        $where = input('param.');
        if(empty($where))return jsonData(404, '未接收到数据', null);
        $map =[
            'user_id' => $user['userid']
        ];
        $db = Db::name('red_cash_log');
        $limit = $where['limit']?$where['limit']:10;
        $page = ($where['page']-1)*$limit;
        $res = $db
            ->where($map)
            ->order('created desc')
            ->field('id,audio,words,menoy,shop_id,created')
            ->limit($page,$limit)
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
            return jsonData(1, '未查到到数据', null);
        }
    }

    /***
     * 用户 - 红包支出
     * @参数 page       页数
     * @参数 limit      条数
     */
    public function expenditure(){
        //用户信息
        $user    = $this->p_auth->session();

        $where = input('param.');
        if(empty($where))return jsonData(404, '未接收到数据', null);
        $map =[
            'user_id' => $user['userid'],
            'status' => 1,
            'pay_time' => ['>',0],
            'offset_money' => ['>',0]
        ];
        $db = Db::name('orders');
        $limit = $where['limit']?$where['limit']:10;
        $page = ($where['page']-1)*$limit;
        $res = $db
            ->where($map)
            ->order('pay_time desc')
            ->field('order_sn,shop_id,pay_time,offset_money')
            ->limit($page,$limit)
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
            return jsonData(1, '未查到到数据', null);
        }
    }
}