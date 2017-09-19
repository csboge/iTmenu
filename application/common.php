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
        $imgUrl = GET_IMG_URL.$info[$field];
    else
        $imgUrl = false;
    return $imgUrl;
}

//多余汉字以点显示
function cut_str($str,$len,$suffix="..."){
    if(function_exists('mb_substr')){
        if(strlen($str) > $len){
            $str= mb_substr($str,0,$len).$suffix;
        }
        return $str;
    }else{
        if(strlen($str) > $len){
            $str= substr($str,0,$len).$suffix;
        }
        return $str;
    }
}

function ajaxSuccess($code, $message = '', $data = []){
    $data = ['code'=>$code,'message'=>$message,'data'=>$data];

    return json($data);
}

function shop_name($id){
    $db = new \think\Db;
    $name = $db::name('shop')->where('id',$id)->field('title')->find();
    return $name['title'];
}

function table_name($id){
    $db = new \think\Db;
    $name = $db::name('table_list')->where('id',$id)->field('name')->find();
    return $name['name'];
}

function goods_name($id){
    $db = new \think\Db;
    $name = $db::name('category')->where('id',$id)->field('name')->find();
    return $name['name'];
}

function package_name($id){
    $db = new \think\Db;
    $name = $db::name('package')->where('id',$id)->field('name')->find();
    return $name['name'];
}


//获取数量
function count_list($name,$type='',$volue='',$status = '1'){
    if(!empty($type) && !empty($volue)){
        $map = [
            $type => $volue,
            'hd_status' => $status
        ];
    }else{
        $map = [
            'hd_status' => $status
        ];
    }
    $db = new \think\Db;
    $count = $db::name($name)->where($map)->count();
    return $count;
}

//获取数量
function count_user($name,$type='',$volue=''){
    $map = [];
    if(!empty($type) && !empty($volue)){
        $map = [
            $type => $volue,
        ];
    }
    $db = new \think\Db;
    $count = $db::name($name)->where($map)->count();
    return $count;
}

//获取二级分类
function grt_category($name,$type,$volue,$status = '1'){
    $map = [
        $type           => $volue,
        'hd_status'     => $status,
        'status'        =>$status
    ];

    $db = new \think\Db;
    $count = $db::name($name)->field('id,parent_id,name')->where($map)->select();
    return $count;
}

//错误日志存储
function my_log($name,$id,$url,$status,$explain){
    $map = [
        'table_name'    => $name,
        'table_id'      => $id,
        'url'           => $url,
        'table_status'  => $status,
        'explain'       => $explain,
        'created'       => date('Y-m-d H:i:s',time())
    ];
    $db = new \think\Db();
    $db::name('log')->insertGetId($map);
}

