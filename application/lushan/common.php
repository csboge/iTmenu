<?php
if (!function_exists('jsonDataInfo')) {

    /**
     * API 公共方法，统一格式化(数据输出)
     * @param int       $code    状态码
     * @param string    $message 操作说明
     * @param array     $data    返回数据
     * @return array
     */
    function jsonDataInfo($code, $message = '', $data = [])
    {
        return ['code'=>$code, 'message'=>$message, 'data'=>$data];
    }
}


/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string
 */
function tplus_ucenter_md5($str, $key = 'T+orTplus'){
    return '' === $str ? '' : md5(sha1($str) . $key);
}

/**
 *查询用户名
 * @param $user_id
 * @return array|false|mixed|PDOStatement|string|\think\Model
 */
function userName($user_id){
    $db = new \think\Db();

    $user_name = $db::name('user')->where('id',$user_id)->field('nickname')->find();

    return $user_name['nickname'];
}
