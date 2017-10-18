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
        \app\api\provider\Xmf            $p_xmf
    )
    {
        //验证授权合法
        $p_auth->check($request, [
            'public' => ['*'],
            'private'=> []
        ]);

        //授权服务
        $this->p_auth   = $p_auth;

        //钟林飞
        $this->p_zlf    = $p_zlf;

        //童鹏
        $this->p_dp     = $p_dp;

        //周莉
        $this->p_zl     = $p_zl;

        //肖茂峰
        $this->p_xmf    = $p_xmf;

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
        else
        {
            $data = [];
        }
        return jsonData(1, 'ok', $data);
    }



    public function details(){
        $id = input('param.id/d');
        if(empty($id))return jsonData(0, '未接收到数据', []);
        $data = [
            1   => [
                GET_IMG_URL.'lecturer/details/1.jpg'
            ],
            2   => [
                GET_IMG_URL.'lecturer/details/2.jpg'
            ],
            3   => [
                GET_IMG_URL.'lecturer/details/3.jpg'
            ],
            4   => [
                GET_IMG_URL.'lecturer/details/4.jpg'
            ]
        ];
        return jsonData(1, 'ok', $data[$id]);
    }
}
