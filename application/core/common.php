<?php
//下载文件到本地
function GrabImage($url, $dir, $filename=''){
    if(empty($url)){
        return false;
    }
    $ext = strrchr($url, '.');
    if($ext != '.gif' && $ext != ".jpg" && $ext != ".bmp"){
        echo "格式不支持！";
        return false;
    }

    $dir = realpath($dir);
    //目录+文件
    $filename = (empty($filename) ? '/'.time().''.$ext : '/'.$filename);
    $filename = $dir . $filename;
    //开始捕捉
    ob_start();
    readfile($url);
    $img = ob_get_contents();
    ob_end_clean();
    $size = strlen($img);
    $fp2 = fopen($filename , "a");
    fwrite($fp2, $img);
    fclose($fp2);
    return $filename;
}