<?php

namespace app\lushan\controller;


use think\Controller;
use think\Request;
use think\Db;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/15
 * Time: 16:09
 */
class Index extends Controller
{
    private $m_order;
    private $m_useradmin;

    /***
     * 注入依赖
     *
     */
    public function __construct()
    {
        parent::__construct();
        //管理员模型
        $this->m_useradmin  = new \app\core\model\UserAdmin();

        //订单模型
        $this->m_order      = new \app\core\model\Orders();

    }


    public function index(){
        if($this->request->param('shop_id')){
            $shop_id = $this->request->param('shop_id');

            //获取订单统计数据
            $order = $this->m_order->orderStatistics($shop_id);

            $order['total_price']   = $order['total_price']?$order['total_price']:0;
            $order['shop_price']    = $order['shop_price']?$order['shop_price']:0;

            $info = [
                [
                    'key'       => '操作系统',
                    'value'     => PHP_OS
                ],[
                    'key'       => '运行环境',
                    'value'     => $_SERVER["SERVER_SOFTWARE"]
                ],[
                    'key'       => 'PHP运行方式',
                    'value'     => php_sapi_name()
                ],[
                    'key'       => 'ThinkPHP版本',
                    'value'     => THINK_VERSION.' <a href="http://thinkphp.cn" target="_blank">[ 查看最新版本 ]</a>'
                ],[
                    'key'       => '上传附件限制',
                    'value'     => ini_get('upload_max_filesize')
                ],[
                    'key'       => '执行时间限制',
                    'value'     => ini_get('max_execution_time').'秒'
                ],[
                    'key'       => '服务器时间',
                    'value'     => date("Y年n月j日 H:i:s")
                ],[
                    'key'       => '北京时间',
                    'value'     => gmdate("Y年n月j日 H:i:s",time()+8*3600)
                ],[
                    'key'       => '服务器域名/IP',
                    'value'     => $_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]'
                ],[
                    'key'       => '剩余空间',
                    'value'     => round((disk_free_space(".")/(1024*1024)),2).'M'
                ]
            ];

            $this->assign('info',$info);
            $this->assign('order',$order);
        }else{
            $this->redirect('Index/login');
        }

        return view();
    }

    /**
     *  登录验证
     * @return array|\think\response\View
     */
    public function login(){

        if(input('post.')){

            $data = input('post.');

            if(empty($data))
            {
                return jsonDataInfo(-1,'传输错误，请重新尝试');
            }

            $admin = $this->m_useradmin->isUserName($data['username']);

            if(empty($admin))
            {
                return jsonDataInfo(-2,'用户名错误');
            }

            $rand = $admin['rand'];

            $password = tplus_ucenter_md5($data['password'],$rand);//加密

            if($admin['password'] !== $password)
            {
                return jsonDataInfo(-3,'密码错误');
            }

            session('shop_id',$admin['shop_id']);

            return jsonDataInfo(1,'登录成功',$admin['shop_id']);
        }

        return view();
    }

}