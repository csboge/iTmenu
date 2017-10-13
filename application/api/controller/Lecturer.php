<?php
namespace app\api\controller;

class Lecturer
{
    public function index()
    {
        $id = input('param.id/d');
        if(empty($id))return jsonData(0, '未接收到数据', []);
        $data = [
            1 => [
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
            ],
            2 => [
                'name'          => '童鹏',
                'explain'       => '好望角集团湖北分总司总经理',
                'portrait'      => GET_IMG_URL.'lecturer/dp/portrait.jpg',
                'phone'         => '18671621319',
                'banner'        => [
                    '1'             => GET_IMG_URL.'lecturer/dp/2.jpg',
                    '2'             => GET_IMG_URL.'lecturer/dp/4.jpg',
                    '3'             => GET_IMG_URL.'lecturer/dp/1.jpg',
                    '4'             => GET_IMG_URL.'lecturer/dp/3.jpg',
                    '5'             => GET_IMG_URL.'lecturer/dp/5.jpg',
                    '6'             => GET_IMG_URL.'lecturer/dp/6.jpg',
                    '7'             => GET_IMG_URL.'lecturer/dp/7.jpg',
                    '8'             => GET_IMG_URL.'lecturer/dp/8.jpg'
                ],
                'describe'      => [
                    '他曾经做推销连200块的房租都交不起！',
                    '他曾经被人认为不适合做销售',
                    '他曾经拜访客户到晚上12点！',
                    '他曾经每天陌生拜访至少30家客户，连续三个月，结果依然不好，',
                    '但他坚信他是一个干大事的人，他下定决心改变自己',
                    '和家族的命运，',
                    '于是他大量的学习和练习销售，他的生命开始发生奇迹般的改变'
                ],
                'dream'      => [
                    '他此生梦想点亮',
                    '一亿人学会销售改变命运！',
                    '并协助恩师黄佰胜老师建立千',
                    '年学府，恩泽子孙后代一千年!',
                    '他被无数的小伙伴誉为最帅、',
                    '最有魅力、最有爱心、最具',
                    '亲和力最实战的超级',
                    '演说家'
                ],
                'deeds'         => [
                    1               => [
                        'title'         => '21岁',
                        'content'       => '成为好望角集团湖北分公司总经理'
                    ],
                    2               => [
                        'title'         => '22岁',
                        'content'       => '巡回全中国做演讲'
                    ],
                    3               => [
                        'title'         => '24岁',
                        'content'       => '年收入超过50万'
                    ],
                    4               => [
                        'title'         => '25岁',
                        'content'       => '全款买上奔驰轿车'
                    ],
                    5               => [
                        'title'         => '26岁',
                        'content'       => '买上百万房子送给老婆当结婚礼物'
                    ],
                    6               => [
                        'title'         => '',
                        'content'       => '影响学员超过30万'
                    ]
                ]
            ],
        ];
        return jsonData(1, 'ok', $data[$id]);
    }
}
