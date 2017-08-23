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

        date_default_timezone_set('PRC'); //设置时区为东八区否则时间比北京时间早8 小时 
        $url = 'http://106.14.55.160:9000/HttpSmsMt';//接口地址 
        $mttime=date("YmdHis"); 
        $name = 'yxcs03';//开通的用户名 
        $password='d41d8cd98f00b204e9800998ecf8427e';//密码 
        $post_data['name'] = $name; 
        $post_data['pwd'] = md5($password.$mttime); 
        $post_data['content'] = '【伯格网络】验证码'. rand(10000, 99999).'。'; //$post_data['content'] = '123456'; 语音验证码内容 
        $post_data['phone'] = $phone;//'15084852913';//手机号码 $post_data['subid'] = ''; 
        $post_data['mttime']= $mttime; 
        $post_data['rpttype'] = '1'; 
        $o = ""; 
        
        //foreach ( $post_data as $k => $v ) { 
        //    $o.= "$k=" . urlencode( $v ). "&" ; 
        //} 
        
        //$post_data = substr($o,0,-1); 
        //$res = request_post($url, $post_data); 


        $host    = '106.14.55.160'; 
        $port    = 9000;
        $client  = new \app\core\provider\HttpClient($host, $port);

        $path = '/HttpSmsMt';
        if(!$client->post($path, $post_data)){
            print 0;

        }else{
            $res = $client->getContent();
            print $res;
        }

        
        return true;
    }





}