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
     * @param   string   $openid        用户openid
     *
     * @return  result
     *
     */
    public function isUserAdmin($openid){
        $rew = $this->where('openid',$openid)->field('user_id,shop_id')->find()->toArray();
        return $rew;
    }

}