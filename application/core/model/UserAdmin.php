<?php
namespace app\core\model;

use think\Model;
use think\db;

/**
 * 用户财务日志方面 * 模型 * 操作方法
 *
 *
 *
 */
class UserAdmin extends Model
{

    /**
     * 查询用户（手机号）
     *
     * @param   string   $mobile        用户手机号
     *
     * @return  result
     *
     */
    public function isUserAdmin($mobile){
        $rew = $this->where('mobile',$mobile)->find();

        return $rew;
    }

}