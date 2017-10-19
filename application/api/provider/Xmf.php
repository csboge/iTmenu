<?php
namespace app\api\provider;

/**
 *  肖茂峰 * 个人资料
 */
class Xmf
{

    public function __construct(){}
    

    /**
     * 获得信息内容
     *
     * @return  string 
     *
     */
    public function getUser()
    {
        $data = [
            'name'          => '肖茂峰',
            'explain'       => '好望角高级内训师',
            'portrait'      => GET_IMG_URL.'lecturer/xmf/portrait.jpg?'.GET_RAND,
            'phone'         => '15956540455',
            'bg'            => GET_IMG_URL.'lecturer/xmf/bg.jpg?'.GET_RAND,
            'deeds'         => [
                1               => [
                    'title'         => '2009年',
                    'content'       => '流水线'
                ],
                2               => [
                    'title'         => '2012年',
                    'content'       => '送货员'
                ],
                3               => [
                    'title'         => '2014年',
                    'content'       => '服务员'
                ],
                4               => [
                    'title'         => '2015年',
                    'content'       => '外资企业'
                ],
                5               => [
                    'title'         => '2017年',
                    'content'       => '集团导师'
                ]
            ],
            'img' => GET_IMG_URL.'lecturer/xmf/img.jpg?'.GET_RAND,
            'product'        => [
                1               => [
                    'img'           => GET_IMG_URL.'lecturer/xmf/product/1.jpg?'.GET_RAND,
                    'title'         => '中国青年创业导师',
                    'type'          => 0
                ],
                2               => [
                    'img'           => GET_IMG_URL.'lecturer/xmf/product/2.jpg?'.GET_RAND,
                    'title'         => '好望角商学院金牌讲师',
                    'type'          => 0
                ],
                3               => [
                    'img'           => GET_IMG_URL.'lecturer/xmf/product/3.jpg?'.GET_RAND,
                    'title'         => '好望角高级内训师',
                    'type'          => 0
                ],
                4               => [
                    'img'           => GET_IMG_URL.'lecturer/xmf/product/4.jpg?'.GET_RAND,
                    'title'         => '好望角人性商道研究员',
                    'type'          => 0
                ]

            ],
            'join'          => [
                '曾经因为一场演讲点亮了自己的生命',
                '明白了帮助别人实现梦想点亮他人生命才是人生的真谛',
                '讲师站上舞台就是帮助别人实现更大价值',
                '而销售人员的天职就是持续不断地为客户创造价值'
            ],
            'banner'          => [
                GET_IMG_URL.'lecturer/xmf/banner/1.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/xmf/banner/2.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/xmf/banner/3.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/xmf/banner/4.jpg?'.GET_RAND
            ],
            'image'          => [
                GET_IMG_URL.'lecturer/xmf/photo/1.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/xmf/photo/2.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/xmf/photo/3.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/xmf/photo/4.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/xmf/photo/5.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/xmf/photo/6.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/xmf/photo/7.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/xmf/photo/8.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/xmf/photo/9.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/xmf/photo/10.jpg?'.GET_RAND,
            ],
        ];

        //返回内容
        return $data;
    }

}