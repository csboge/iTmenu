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
        $path = [
            'path' =>GET_IMG_URL.'/video/'.$info->getSaveName(),
        ];
        return $path;
    }else{
        return false;
    }
}

/***
 * 获取 -- 获取商铺名称和logo
 * @参数 id      id
 */
function shop_title($id){
    $db = new \think\Db();
    $data = $db::name('shop')->where(['id'=>$id])->field('title,logo')->find();
    return $data;
}

/***
 * 判断 -- 是否已领取优惠券
 * @参数 user_id    用户id
 * @参数 coupon_id  优惠券id
 * @参数 shop_id    店铺id
 */
function is_coupon($user_id,$coupon_id,$shop_id){
    $db = new \think\Db();
    $map = [
        'user_id' => $user_id,
        'coupon_id' => $coupon_id,
        'shop_id' => $shop_id
    ];
    $data = $db::name('coupon_list')->where($map)->find();
    return $data;

}

