<?php
if (!function_exists('jsonData')) {

    /**
     * API 公共方法，统一格式化(数据输出)
     * @param int       $code    状态码
     * @param string    $message 操作说明
     * @param array     $data    返回数据
     * @return array
     */
    function jsonData($code, $message = '', $data = [])
    {
        return ['code'=>$code, 'message'=>$message, 'data'=>$data];
    }
}
