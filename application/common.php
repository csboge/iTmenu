<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
if (!function_exists('getable')) {
    /**
     * 快速导入Traits PHP5.5以上无需调用
     * @param string    $class trait库
     * @param string    $ext 类库后缀
     * @return boolean
     */
    function getable($tabname, $number)
    {
        return '';
    }
}

//获取图片
function getImgUrl($id,$field = 'path'){
    $db = new \think\Db;
    $info = $db::name('picture')->where(['id'=>$id])->find();
    if($info)
        $imgUrl = \think\Request::instance()->root(true).'/'.$info[$field];
    else
        $imgUrl = false;
    return $imgUrl;
}

//获取图片
function ImgUrl($id,$field = 'path'){
    $db = new \think\Db;
    $info = $db::name('picture')->where(['id'=>$id])->find();
    if($info)
        $imgUrl = $info[$field];
    else
        $imgUrl = false;
    return $imgUrl;
}