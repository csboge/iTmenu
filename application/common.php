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

/***
 * 上传 -- base64
 * @参数 file      文件
 */
function base64_img($base_64,$type='')
{
    if(!$type){
        preg_match('/^(data:\s*image\/(\w+);base64,)/', $base_64, $result);
        $type = $result[2];//图片格式
    }
    $pattern = '/data:.*base64/U';
    $res = preg_replace($pattern, '', $base_64);
    $str = base64_decode($res);
    $md5 = md5($str);
    $sha1 = sha1($str);
    $db = new \think\Db;
    $re = $db::name('picture')->where(['md5' => $md5, 'sha1' => $sha1])->find();
    if ($re) {
        return $re['id'];
    } else {
        $url = 'picture/' . date('Ymd', time()) . '/';
        $PATH = ROOT_PATH . 'Uploads/picture/' . date('Ymd', time()) . '/';
        if (!is_dir($PATH)) {
            mkdir($PATH, 0777, true);
        }//判断目录是否存在，不存在则创建
        $name = md5(rand(100, 999) . time());
        $imgPath = $PATH . $name . '.' . $type;
        file_put_contents($imgPath, $str);
        $data = [
            'path' => $url . $name . '.' . $type,
            'md5' => $md5,
            'sha1' => $sha1,
            'status' => 1,
            'create_time' => time(),
        ];
        $id = $db::name('picture')->insertGetId($data);
        return $id;
    }

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
            'hd_status' => $status,
        ];
    }else{
        $map = [
            'hd_status' => $status,
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
    $db::name('log')->insert($map);
}

//请求post带参请求url
function api_notice_increment($url, $data){
    $ch = curl_init();
    $header = array("Accept-Charset: utf-8");
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $tmpInfo = curl_exec($ch);
    //     var_dump($tmpInfo);
    //    exit;
    if (curl_errno($ch)) {
        return false;
    }else{
        // var_dump($tmpInfo);
        return $tmpInfo;
    }
}

