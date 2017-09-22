<?php
namespace app\api\controller;

use think\Request;
use think\db;

class Discount
{
    use \app\core\traits\ProviderFactory;


    private     $p_auth;
    private     $request;

    /***
     * 公共 - 注入依赖
     *
     */
    public function __construct(
        Request                         $request,
        \app\core\provider\Auth         $p_auth,
        \app\core\model\RedCash         $m_red,
        \app\core\model\RedCashLog      $m_red_log,
        \app\core\model\UserAccount     $m_acc_log,
        \app\core\model\Orders          $m_order,
        \app\core\provider\Orders       $p_order,
        \app\core\model\User            $m_user,
        \app\core\provider\RedCashLog   $p_redcashlog,
        \app\core\model\Banner          $m_banner
    )
    {
        //验证授权合法
        $p_auth->check($request, [
            'public' => ['robInfo','redList','robbed'],
            'private'=> []
        ]);

        //授权服务
        $this->p_auth           = $p_auth;

        //红包模型
        $this->m_red            = $m_red;

        //红包模型(抢夺日志)
        $this->m_red_log        = $m_red_log;

        //财务日志模型(交易日志)
        $this->m_acc_log        = $m_acc_log;

        //订单模型
        $this->m_order          = $m_order;

        //订单服务
        $this->p_order          = $p_order;

        //用户模型
        $this->m_user           = $m_user;

        //抢红包服务
        $this->p_redcashlog     = $p_redcashlog;

        //抢红包模型
        $this->m_banner         = $m_banner;

        //获取当前控制器名
        $this->request = \think\Request::instance();
    }



    /***
     * 红包 - 生成
     */
    function create()
    {

        //订单号
        $ordersn    = input('param.ordersn/s');

        //语音口令
        $words      = input('param.words/s');

        if(!$ordersn) { 
            return jsonData(-1, '订单号 - 不能为空。');
        }

        if(!$words) { 
            return jsonData(-2, '语音口令 - 不能为空。');
        }

        //用户信息
        $session    = $this->p_auth->session();

        //获得商店id
        $shopid     = $this->p_auth->getShopId();


        //查询订单 - 是否已生成红包
        $red_info   = $this->m_red->getRedForOrderSN($ordersn);
        if($red_info) {
            return jsonData(-3, '该订单 - 已生成红包。');
        }

        //查询订单
        $order_info            = $this->m_order->getOrderForSN($ordersn);
        if(!$order_info) {
            return jsonData(-4, '该订单 - 已不存在。' . $this->m_order->getLastSql());
        }


        $num  = rand($order_info['user_count'], $order_info['user_count'] * 2);
        $data = [
            'order_sn'  => $ordersn,
            'shop_id'   => $order_info['shop_id'],
            'menoy'     => $order_info['mode_money'],
            'surplus'   => $order_info['mode_money'],
            'user_id'   => $session['userid'],
            'words'     => $words,
            'num'       => $num,
            'created'   => time(),
            'updated'   => time(),
        ];
        $result = $this->m_red->data($data)->save();

        if (!$result) {
            return jsonData(0, '抱歉 - 生成失败。');
        }


        //生成红包 - 内存变量 - 控制抢夺并发
        $redis = $this->redisFactory();
        $redis->set('discount:red:' . $this->m_red->id, $num);

        //红包详细
        $data['id'] = $this->m_red->id;
        $redis->set('discount:redinfo:' . $this->m_red->id, json_encode($data));


        //一条红包信息                              红包总数      已抢数量
        $baginfo    = ['bagid'=>$this->m_red->id, 'count'=>$num, 'speed'=>0];

        return jsonData(1, 'ok', $baginfo);
    }


    public function asd(){
        if(isset($baginfo['use_users'])){
            echo 1111111;
        }else{
            echo 22222222;
        }
    }

    /***
     * 红包 - 抢夺
     */
    function robbed()
    {

        //红包id
        $bagid      = input('param.bagid/d');


        //语音口令(二进制)
        $audio      = request()->file('audio');//input('param.audio');

        //商店id
        $shop       = $this->p_auth->getShopId();

        //用户信息
        $session    = $this->p_auth->session();

        my_log('orders',$bagid,'api/discount/robbed',0,'用户信息');
        $redis = $this->redisFactory();

        //合法验证
        $bagstr    = $redis->get('discount:redinfo:' . $bagid);

        if (!$bagstr) {
            return jsonData(-1, '红包已经过期了' . $bagid);
        }

        //红包时间超过24小时
        $redTime = $this->m_red->endTime($bagid);       //红包创建时间
        $time = time();                                 //当前时间
        $data = ($time-$redTime['created'])/(60*60);               //距离当前时间
        if($data >= 24){
            return jsonData(-1, '红包已经过期了' . $bagid);
        }

        $baginfo    = json_decode($bagstr, true);

        $baginfo['use_users'] = '';
        //是否还可以抢夺
        $is_user = $this->m_red_log->isUserRed($session['userid'],$bagid);
        if($is_user){
            return jsonData(-6, '嗨，你已经抢过了');
        }
//        if (isset($baginfo['use_users'])){
//            if (in_array($session['userid'], explode(',', $baginfo['use_users']))) {
//                return jsonData(-6, '嗨，你已经抢过了');
//            }
//        }


        //剩余红包数量
        $nums  = $redis->DECR('discount:red:' . $bagid);
        if ($nums <= -1) {
            return jsonData(-2, '红包已经被抢完了');
        }

        //本次抢夺金额
        $my_money = $this->m_red->getMoney($baginfo['surplus'], $nums+1, $baginfo['num']);

        if ($my_money <= 0) {
            return jsonData(-5, '红包已经被抢完了');
        }


        //更新缓存
        $baginfo['surplus'] -= $my_money;

        //记录已抢用户id
        $baginfo['use_users'] .= ',' . $session['userid'];

        $baginfo['updated']  = time();
        $redis->set('discount:redinfo:' . $bagid, json_encode($baginfo));


        //红包完结 - 可能要做的清理工作。
        if ($nums <= 0) { }


        /**
         * 上传 语音口令文件..
         * 保存 文件地址
         * 
         *
         */
        $video_url = upload_video($audio);

        $audio_url = GET_VIDEO_URL.$video_url;


        // 开启 - 数据库事务
        Db::startTrans();
        try{

            //生成一条红包 - 抢夺日志
            $data = [
                'red_cash_id'   => $bagid,
                'user_id'       => $session['userid'],
                'audio'         => $audio_url,
                'words'         => $baginfo['words'],
                'menoy'         => $my_money,
                'shop_id'       => $baginfo['shop_id'],
                'remark'        => '抢夺了一个[语音红包]，金额：￥' . $my_money,
                'created'       => time(),
                'updated'       => time()
            ];
            $ret1  = $this->m_red_log->data($data)->save();


            //母包 - 更新信息
            $get_num = $baginfo['num'] - $nums;
            $data  = [
                'surplus' => $baginfo['surplus'],
                'get_num' => $get_num,
                'updated' => time()
            ];
            $ret2  = $this->m_red->save($data, ['id' => $bagid, 'order_sn'=>$baginfo['order_sn']]);


            //增加 - 财务日志
            $logsn = $this->p_order->getOrderSN();      //交易号
            $data  = [
                'logsn'         => $logsn,
                'type'          => 0,                   //0=收入，1=支出
                'money'         => $my_money,
                'user_id'       => $session['userid'],
                'shop_id'       => $baginfo['shop_id'],
                'order_sn'      => $baginfo['order_sn'],
                'red_cash_id'   => $bagid,
                'created'       => time()
            ];
            $ret3  = $this->m_acc_log->data($data)->save();



            //统计 - 用户账户(红包余额)
            $acc_money  = $this->m_user->where('id', $session['userid'])->value('money');
            $acc_money += $my_money;

            $data  = [
                'money' => $acc_money,
                'updated' => time()
            ];
            
            $ret4  = $this->m_user->save($data, ['id' => $session['userid']]);



            // 提交事务
            if ($ret1 && $ret2 && $ret3 && $ret4) {
                Db::commit();

                //获得商店id
                $shop           = $this->p_auth->getShopId();

                //获得抢红包记录
                $list_red       = $this->p_redcashlog->getRedList($shop,$bagid);

                my_log('orders',$list_red,'api/discount/robbed',0,'提交事务');
                //抢到红包金额     已抢数量
                return jsonData(1, 'ok', $list_red);
            }

            my_log('orders',$bagid,'api/discount/robbed',0,'红包抢夺失败了');
            return jsonData(-3, '抱歉 - 红包抢夺失败了');



        // 回滚事务
        } catch (\Exception $e) {
            Db::rollback();

            return jsonData(-4, '发生故障 - 红包抢夺发生错误');
        }
    }



    /***
     * 红包 - 生成朋友圈 - 转发图片
     */
    function robimg()
    {

        //红包id
        $bagid      = input('param.bagid/d');


        //用户信息
        $session    = $this->p_auth->session();


     
        return jsonData(1, 'ok', ['imgurl'=>'']);
    }

    /***
     * 红包 - 领取人信息
     */
    public function redList(){
        //红包id
        $bagid      = input('param.bagid/d');

        //商店id
        $shop       = $this->p_auth->getShopId();

        //抢红包记录
        $list_red   = $this->p_redcashlog->getRedList($shop,$bagid);

        return jsonData(1, 'ok', $list_red);
    }


    /***
     * 红包 - 初始红包页
     */
    function robInfo()
    {
        //红包id
        $bagid      = input('param.bagid/d');

        //商店id
        $shop       = $this->p_auth->getShopId();

        //红包信息
        $redlist        = $this->m_red->getRedList($bagid);

        //抢红包记录
        $list_red   = $this->p_redcashlog->getRedList($shop,$bagid);

        //轮播图
        $banner     = $this->m_banner->isBanner($shop);
        foreach ($banner as &$base){
            $base['image'] = ImgUrl($base['image']);
        }

        //一条红包信息
        $baginfo    = [
            'bagid'=>$bagid,
            'menoy'=> $redlist['menoy'],
            'surplus'=> $redlist['surplus'],
            'num'=> $redlist['num'],
            'get_num'=> $redlist['get_num'],
            'words'=> $redlist['words'],
            'user_list'=>$list_red,
            'banners' => $banner
        ];

        return jsonData(1, 'ok', $baginfo);
    }



}