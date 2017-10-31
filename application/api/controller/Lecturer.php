<?php
namespace app\api\controller;

use think\Request;

class Lecturer
{
    private     $p_auth;

    /***
     * 公共 - 注入依赖
     *
     */
    public function __construct(
        Request                         $request,
        \app\core\provider\Auth         $p_auth,
        \app\api\provider\Zlf           $p_zlf,
        \app\api\provider\Dp            $p_dp,
        \app\api\provider\Zl            $p_zl,
        \app\api\provider\ZlDetails     $p_zldetails,
        \app\api\provider\Xmf           $p_xmf,
        \app\api\provider\Zly           $p_zly,
        \app\api\provider\ZlyDetails    $p_zlydetails,
        \app\api\provider\Zw            $p_zw,
        \app\api\provider\ZwDetails     $p_zwdetails,
        \app\api\provider\Ly            $p_ly
    )
    {
        //验证授权合法
        $p_auth->check($request, [
            'public' => ['*'],
            'private'=> []
        ]);

        $this->p_auth           = $p_auth;          //授权服务

        $this->p_zlf            = $p_zlf;           //钟林飞

        $this->p_dp             = $p_dp;            //童鹏

        $this->p_zl             = $p_zl;            //周莉

        $this->p_zldetails      = $p_zldetails;     //周莉详情

        $this->p_xmf            = $p_xmf;           //肖茂峰

        $this->p_zly            = $p_zly;           //张丽雅

        $this->p_zlydetails      = $p_zlydetails;   //周莉详情

        $this->p_zw             = $p_zw;            //周伟

        $this->p_zwdetails      = $p_zwdetails;     //周伟详情

        $this->p_ly             = $p_ly;            //卢煜
    }


    public function index()
    {
        $id = input('param.id/d');
        if(empty($id))return jsonData(0, '未接收到数据', []);
        if($id == 1)
        {
            $data = $this->p_zlf->getUser();            //获取 钟林飞个人资料
        }
        elseif($id == 2)
        {
            $data = $this->p_dp->getUser();             //获取 童鹏个人资料
        }
        elseif ($id == 3)
        {
            $data = $this->p_zl->getUser();             //获取 周莉个人资料
        }
        elseif ($id == 4)
        {
            $data = $this->p_xmf->getUser();            //获取 肖茂峰个人资料
        }
        elseif ($id == 5)
        {
            $data = $this->p_zly->getUser();            //获取 张丽雅个人资料
        }
        elseif ($id == 6)
        {
            $data = $this->p_zw->getUser();             //获取 周伟个人资料
        }
        elseif ($id == 7)
        {
            $data = $this->p_ly->getUser();             //获取 卢煜个人资料
        }
        else
        {
            $data = [];
        }
        return jsonData(1, 'ok', $data);
    }


    //周莉 ** 个人资料详情页
    public function details(){
        $id = input('param.id/d');
        if(empty($id))return jsonData(0, '未接收到数据', []);
        $data = $this->p_zldetails->getUser($id);
        return jsonData(1, 'ok', $data);
    }

    //张丽雅 ** 个人资料详情页
    public function zly_details(){
        $id = input('param.id/d');
        if(empty($id))return jsonData(0, '未接收到数据', []);
        $data = $this->p_zlydetails->getUser($id);
        return jsonData(1, 'ok', $data);
    }


    //周伟 ** 个人资料详情页
    public function zw_details(){
        $id = input('param.id/d');
        if(empty($id))return jsonData(0, '未接收到数据', []);
        $data = $this->p_zwdetails->getUser($id);
        return jsonData(1, 'ok', $data);
    }
}
