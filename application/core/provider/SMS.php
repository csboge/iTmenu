<?php
namespace app\core\provider;

/**
 * 短信方面 * 服务 * 操作方法
 *
 *
 *
 */
class SMS
{

    public function __construct(){}
    

    /**
     * 获得信息内容
     *
     * @param   string   $params   业务参数
     * @param   string   $index    索引
     *
     * @return  string 
     *
     */
    public function getWords($params, $index)
    {
        //不合法，直接返回
        if (!isset($msg[$index])) return null;

        $msg            = [];
        $msg['getcode'] = $params['code'];

        //返回内容
        return $msg[$index];
    }



    /**
     * 发送手机短信
     *
     * @param   string   $mobile    手机号码
     * @param   string   $words     短信内容
     *
     * @return  bool
     */
    public function send($mobile, $words)
    {

        return true;
    }





}