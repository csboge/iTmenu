<?php
namespace app\api\controller;


class Lecturers
{

    /***
     * 公共 - 注入依赖
     *
     */
    public function __construct(
        \app\api\provider\Daikuan       $p_daikuan
    )
    {

        $this->p_daikuan        = $p_daikuan;       //贷款
    }


    public function index()
    {
        $data = $this->p_daikuan->getUser();        //获取 贷款资料

        return jsonData(1, 'ok', $data);
    }

}
