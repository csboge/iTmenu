<?php
namespace app\core\provider;

use think\Db;

/**
 * 商户入驻方面 * 服务 * 操作方法
 *
 *
 *
 */
class Shopjoin
{
    private $m_shop;

    public function __construct()
    {
        //商户入驻模型
        $this->m_shop = new \app\core\model\Shopjoin();
    }



    /**
     * 商家入驻
     *
     * @param   string   $data    商户资料
     * 
     * 
     *
     * @return  string 
     *
     */
    public function getShopJoin($data)
    {

        //添加商家的信息
        $row = $this->getShopJoin($data);

        
        return $row;
    }


       /**
     * 商家信息查询
     *
     * @param   string   $shopunid    商户信息
     * 
     * 
     *
     * @return  string 
     *
     */
    public function getShopJoin($shopunid)
    {

        //查询商家的信息
        $row = $this->getShopJoins(trim($shopunid));

        
        return $row;
    }








}