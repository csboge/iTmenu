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
        \app\api\provider\Xmf           $p_xmf
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


    //周莉 ** 个人资料详情页
    public function details(){
        $id = input('param.id/d');
        if(empty($id))return jsonData(0, '未接收到数据', []);
        $data = [
            1   => [
                GET_IMG_URL.'lecturer/details/tc3_paf1.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_paf2.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_paf3.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_paf4.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_paf5.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_paf6.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_paf7.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_paf8.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_paf9.jpg?'.GET_RAND
            ],
            2   => [
                GET_IMG_URL.'lecturer/details/tc3_xy1.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_xy2.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_xy3.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_xy4.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_xy5.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_xy6.jpg?'.GET_RAND
            ],
            3   => [
                GET_IMG_URL.'lecturer/details/tc3_et1.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_et2.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_et3.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_et4.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_et5.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_et6.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_et7.jpg?'.GET_RAND
            ],
            4   => [
                GET_IMG_URL.'lecturer/details/tc3_pa1.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_pa2.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_pa3.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_pa4.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_pa5.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_pa6.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_pa7.jpg?'.GET_RAND
            ]

        ];
        return jsonData(1, 'ok', $data[$id]);
    }
}
