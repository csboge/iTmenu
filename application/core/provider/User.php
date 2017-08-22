<?php
namespace app\core\provider;

use think\Db;

/**
 * 用户方面 * 服务 * 操作方法
 *
 *
 *
 */
class User
{
    private $m_user;

    public function __construct()
    {
        //用户模型
        $this->m_user = new \app\index\model\User();
    }



    /**
     * 用户登录
     *
     * @param   string   $username    用户名
     * @param   string   $password    密码口令
     * @param   array    $logtype     验证方式 0=验证码 1=密码
     *
     * @return  int 
     *
     */
    public function login($mobile, $password, $logtype = 0)
    {

        //验证码 方式
        if ($logtype == 0) {

            $code   = $this->getCode($mobile, 1);
            if ($code == 0) return -1;

            return ($code === $password) ? 1 : 0;
        }


        //查询 手机号
         $row       = $this->m_user->getUserForMobile(trim($mobile));
         if (!$row) return -2;

         //验证密码
         $password  = md5(md5($password) . $row['salt']);
         if ($row['password'] !== $password) {
             return 0;
         }

         return 1;
    }


    /**
     * 获得手机动态口令
     *
     * @param   string   $mobile    手机号码
     * @param   int      $type      是否读取  0=写入, 1=读取
     *
     * @return  int
     */
    public function getCode($mobile, $read = 0)
    {
        /**
         * 基本逻辑
         *
         * if 读取模式，直接返回读取结果；
         *
         * 查询手机号码是否存在，不存在直接返回
         * 生产 验证码
         * 将 验证码 与手机号码关联，并存储起来
         * 
         * 返回结果
         *
         */

         $mckey     = 'login-code-' . $mobile;
         if (intval($read) == 1) return intval(cache($mckey));

         //查询 手机号
         $row       = $this->m_user->getUserForMobile(trim($mobile));
         if (!$row) return 0;

         //生成 验证码
         $code      = intval(rand(100, 999) . rand(100, 999));

         //存储
         cache($mckey, $code);

         //发送验证码
        $this->sms    = new \app\core\provider\SMS();
        $words     = $this->sms->getWords(['code'=>$code], 'getcode');
        $result    = $this->sms->send($mobile, $words);

        return ($result) ? $code : 0;
    }





}