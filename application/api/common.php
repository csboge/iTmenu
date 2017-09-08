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
/**
 * 获取 - 优惠券
 * @param $id - 优惠券id
 */
function coupon($id){
    $db = new \think\Db();
    $data = $db::name('coupon')->where(['id'=>$id])->find();
    return $data;
}

/***
 * 上传 -- ceshi
 * @参数 file      文件
 */
function upload_video($file){
    $info = $file
        ->validate([
            'size'=>145678,
            'ext'=>'mp3,wav,wma,ogg,acc,ape,silk',
        ])
        ->move(ROOT_PATH . 'Uploads' . DS . 'video');
    if($info){
        // 成功上传后 获取上传信息
        $post = [
            'path' =>'/picture/'.$info->getSaveName(),
        ];
        return $post;
    }else{
        return false;
    }
}