<?php
namespace app\api\provider;

/**
 *  周莉 * 个人资料
 */
class Zl
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
            'name'          => '周莉',
            'explain'       => '平安资深主任',
            'portrait'      => GET_IMG_URL.'lecturer/zl/portrait.jpg',
            'phone'         => '18906191509',
            'bg'            => GET_IMG_URL.'lecturer/zl/bg.jpg',
            'deeds'         => [
                1               => [
                    'title'         => '2015-12',
                    'content'       => '入司平安'
                ],
                2               => [
                    'title'         => '2016-07',
                    'content'       => '晋升主任'
                ],
                3               => [
                    'title'         => '2017-01',
                    'content'       => '高级主任'
                ],
                4               => [
                    'title'         => '2017-07',
                    'content'       => '资深主任'
                ],
                5               => [
                    'title'         => '2018-01',
                    'content'       => '冲刺经理'
                ]
            ],
            'product'        => [
                1               => [
                    'img'           => GET_IMG_URL.'lecturer/zl/product/1.jpg',
                    'title'         => '平安福',
                    'type'          => 1
                ],
                2               => [
                    'img'           => GET_IMG_URL.'lecturer/zl/product/2.jpg',
                    'title'         => '玺越人生',
                    'type'          => 1
                ],
                3               => [
                    'img'           => GET_IMG_URL.'lecturer/zl/product/3.jpg',
                    'title'         => '儿童综合医疗',
                    'type'          => 1
                ],
                4               => [
                    'img'           => GET_IMG_URL.'lecturer/zl/product/4.jpg',
                    'title'         => '平安e生保2017',
                    'type'          => 1
                ]

            ],
            'image' => GET_IMG_URL.'lecturer/zl/image.jpg',
            'join'          => [
                '我们相遇相知......一路上的点点滴滴，',
                '一直记在心中！携手共同创业.我们一起努力！',
                '再大的困难，都不是困难，齐心协力，勇往直前！',
                '期待更多的帅哥美女加入大爱团队！'
            ],
            'recruit'          => [
                1               => [
                    'title'         => '助理2名、售后服务专员5名',
                    'content'       => '工资在3000元—5000元'
                ],
                2               => [
                    'title'         => '信用卡专员及银行的存款贷款专员5名',
                    'content'       => '高中以上文凭、底薪3500元加提成、都是双休、'
                ],
                3               => [
                    'title'         => '',
                    'content'       => '节假日正常休息！'
                ]
            ],
            'banner'          => [
                GET_IMG_URL.'lecturer/zl/banner/1.jpg',
                GET_IMG_URL.'lecturer/zl/banner/2.jpg',
                GET_IMG_URL.'lecturer/zl/banner/3.jpg',
                GET_IMG_URL.'lecturer/zl/banner/4.jpg',
                GET_IMG_URL.'lecturer/zl/banner/5.jpg'
            ],
            'photo'          => [
                GET_IMG_URL.'lecturer/zl/photo/1.jpg',
                GET_IMG_URL.'lecturer/zl/photo/2.jpg',
                GET_IMG_URL.'lecturer/zl/photo/3.jpg',
                GET_IMG_URL.'lecturer/zl/photo/4.jpg',
                GET_IMG_URL.'lecturer/zl/photo/5.jpg',
                GET_IMG_URL.'lecturer/zl/photo/6.jpg',
                GET_IMG_URL.'lecturer/zl/photo/7.jpg',
                GET_IMG_URL.'lecturer/zl/photo/8.jpg',
            ],
        ];

        //返回内容
        return $data;
    }

}