<?php
namespace app\api\provider;

/**
 *  钟林飞 * 个人资料
 */
class Zlf
{

    public function __construct(){}
    

    /**
     * 获得信息内容
     *
     * @param   string   $params   业务参数
     * @param   string   $index    索引
     *
     * @return  string 
     *
     */
    public function getUser()
    {
        $data = [
            'name'          => '萧鼎诚',
            'explain'       => '亚洲90后首席创业导师',
            'portrait'      => GET_IMG_URL.'lecturer/zlf/portrait.jpg?'.GET_RAND,
            'phone'         => '18671621319',
            'banner'        => [
                'banner1'       => GET_IMG_URL.'lecturer/zlf/banner1.jpg?'.GET_RAND,
                'banner2'       => GET_IMG_URL.'lecturer/zlf/banner2.jpg?'.GET_RAND,
                'banner3'       => GET_IMG_URL.'lecturer/zlf/banner4.jpg?'.GET_RAND,
                'banner4'       => GET_IMG_URL.'lecturer/zlf/banner5.jpg?'.GET_RAND,
                'banner5'       => GET_IMG_URL.'lecturer/zlf/1.jpg?'.GET_RAND,
                'banner6'       => GET_IMG_URL.'lecturer/zlf/2.jpg?'.GET_RAND,
                'banner7'       => GET_IMG_URL.'lecturer/zlf/3.jpg?'.GET_RAND,
                'banner8'       => GET_IMG_URL.'lecturer/zlf/4.jpg?'.GET_RAND,
            ],
        ];

        //返回内容
        return $data;
    }

}