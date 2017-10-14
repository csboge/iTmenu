<?php
namespace app\api\provider;

/**
 * 短信方面 * 服务 * 操作方法
 *
 *
 *
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
            'name'          => '钟林飞',
            'explain'       => '好望角全国首席讲师',
            'portrait'      => GET_IMG_URL.'lecturer/zlf/portrait.jpg',
            'phone'         => '18671621319',
            'banner'        => [
                'banner1'       => GET_IMG_URL.'lecturer/zlf/banner1.jpg',
                'banner2'       => GET_IMG_URL.'lecturer/zlf/banner2.jpg',
                'banner3'       => GET_IMG_URL.'lecturer/zlf/banner3.jpg',
                'banner4'       => GET_IMG_URL.'lecturer/zlf/banner4.jpg',
                'banner5'       => GET_IMG_URL.'lecturer/zlf/banner5.jpg',
            ],
            'deeds'         => [
                1               => [
                    'title'         => '亚洲90后超级演说家',
                    'content'       => '22岁打破集团9年来的销售记录',
                    'colour'        => '#e0620c'
                ],
                2               => [
                    'title'         => '好望角集团浙江分公司总经理',
                    'content'       => '23岁成为浙江分公司总经理',
                    'colour'        => '#00a0e9'
                ],
                3               => [
                    'title'         => '好望角商学院首席创业导师',
                    'content'       => '24岁仅用九个月成功挑战开上宝马',
                    'colour'        => '#35b16c'
                ],
                4               => [
                    'title'         => '好望角集团董事',
                    'content'       => '27岁成为集团董事帮助团队伙伴开上奔驰宝马',
                    'colour'        => '#959595'
                ]
            ]
        ];

        //返回内容
        return $data;
    }

}