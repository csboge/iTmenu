<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/17
 * Time: 11:38
 */
namespace app\index\model;
use think\Model;
use think\db;
use app\core\traits\InterfaceModel;

class User extends Model 
{


    /**
     * 查询用户（手机号）
     *
     * @param   string   $mobile    手机号
     *
     * @return  result 
     *
     */
    public function getUserForMobile($mobile)
    {
        $row = $this->where('mobile', trim($mobile))->find();

        return $row;
    }



}