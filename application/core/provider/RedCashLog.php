<?php
namespace app\core\provider;

/**
 * 短信方面 * 服务 * 操作方法
 *
 *
 *
 */
class RedCashLog
{

    private         $m_user;
    private         $m_redcashlog;


    public function __construct(){

        //用户模型
        $this->m_user = new \app\core\model\User();

        //抢红包模型
        $this->m_redcashlog = new \app\core\model\RedCashLog();
    }
    

    /**
     * 获得抢红包内容
     *
     * @param   string   $shop      商户id
     * @param   string   $bagid     红包id
     *
     * @return  string 
     *
     */
    public function getRedList($shop,$bagid)
    {
        $list_red = $this->m_redcashlog->isRedList($shop,$bagid);
        foreach ($list_red as &$itme){
            $vcr = $this->m_user->getUserForId($itme['user_id']);
            $itme['nickname'] = $vcr['nickname'];
            $itme['avatar'] = $vcr['avatar'];
            $itme['sex'] = $vcr['sex'];
            unset($itme['user_id']);
        }

        return $list_red;
    }




}