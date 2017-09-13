<?php
namespace app\core\model;

use think\Model;
use think\db;

/**
 * 用户方面 * 模型 * 操作方法
 *
 *
 *
 */
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


     /**
     * 查询用户（unionid）
     *
     * @param   string   $unionid    用户唯一标识
     *
     * @return  result 
     *
     */
    public function getUserForUnionid($unionid)
    {
        $row = $this->where('unionid', trim($unionid))->find();

        return $row;
    }


     /**
     * 查询用户（openid）
     *
     * @param   string   $openid     用户开放唯一标识
     *
     * @return  result 
     *
     */
    public function getUserForOpenid($openid)
    {
        $row = $this->where('openid', trim($openid))->find();

        return $row;
    }

    /**
     * 查询用户余额
     *
     * @param   string   $userid     用户开放唯一标识
     *
     * @return  result
     *
     */
    public function isMoney($userid)
    {
        $row = $this->where('id',$userid)->field('money')->find()->toArray();

        return $row;
    }

    /**
     * 修改用户余额
     *
     * @param   string   $userid     用户开放唯一标识
     *
     * @param   string   $money      用户使用的余额
     *
     * @return  result
     *
     */
    public function userMoney($userid,$money)
    {
        $row = $this->where('id',$userid)->setDec('money',$money);

        return $row;
    }
}