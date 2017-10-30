<?php
namespace app\api\provider;

/**
 *  周伟 * 个人资料
 */
class Zw
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
            'head_bg'       => GET_IMG_URL.'lecturer/zw/index/head_bg.jpg?'.GET_RAND,
            'portrait'      => GET_IMG_URL.'lecturer/zw/index/portrait.jpg?'.GET_RAND,
            'name'          => '周伟',
            'phone'         => 15111276022,
            'explain'       => '好望角汇成商学院创始人',
            'culture'       => [
                'image'     => GET_IMG_URL.'lecturer/zw/index/culture.png?'.GET_RAND,
                'content'   => [
                    [
                        'title'     => '我们的愿景',
                        'content'   => '让中小企业花最少的钱学最落地的课程'
                    ],
                    [
                        'title'     => '我们的使命',
                        'content'   => '解救中小企业于水深火热之中'
                    ],
                    [
                        'title'     => '我们的宗旨',
                        'content'   => '经营人，影响人，成就人'
                    ],
                    [
                        'title'     => '我们的精神',
                        'content'   => '积极、学习、快乐、团结、成功'
                    ],
                    [
                        'title'     => '我们的作风',
                        'content'   => '指哪打哪，雷厉风行，没有借口，永不言败'
                    ],
                    [
                        'title'     => '我们的核心价值观',
                        'content'   => '为天地立心，为生民立命，为往圣继绝学，'
                    ],
                    [
                        'title'     => '',
                        'content'   => '为万世开太平为企业成功全力以赴......'
                    ]
                ]
            ],
            'service'       => [
                GET_IMG_URL.'lecturer/zw/index/company_serv.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/zw/index/company_serv_bg.jpg?'.GET_RAND,
            ],
            'boutique'       => [
                'image'         => GET_IMG_URL.'lecturer/zw/index/class_title.jpg?'.GET_RAND,
                'content'       => [
                    [
                        'title'     => '引流策略',
                        'img'       => GET_IMG_URL.'lecturer/zw/index/item1.jpg?'.GET_RAND
                    ],
                    [
                        'title'     => '赚钱模式',
                        'img'       => GET_IMG_URL.'lecturer/zw/index/item2.jpg?'.GET_RAND
                    ],
                    [
                        'title'     => '暴利营销',
                        'img'       => GET_IMG_URL.'lecturer/zw/index/item3.jpg?'.GET_RAND
                    ],
                    [
                        'title'     => '自动化营销',
                        'img'       => GET_IMG_URL.'lecturer/zw/index/item4.jpg?'.GET_RAND
                    ],
                    [
                        'title'     => '团队密训',
                        'img'       => GET_IMG_URL.'lecturer/zw/index/item5.jpg?'.GET_RAND
                    ],
                    [
                        'title'     => '狼性销售',
                        'img'       => GET_IMG_URL.'lecturer/zw/index/item6.jpg?'.GET_RAND
                    ]
                ]
            ],
            'human'         =>  [
                'image'         => GET_IMG_URL.'lecturer/zw/index/bgS.jpg?'.GET_RAND,
                'title'         => '知人性',
                'content'       => [
                    '通过站在人的角度思考企业的未来。',
                    '知人性者得人心，得人心者得天下！',
                    '管理定江山，团队打天下！',
                    '新的时代不是随便抓住一个机遇就能赚钱的，',
                    '需要的是新的经营模式和一支强大的销售团队共同奋战。'
                ],
                'video'     => [
                    GET_IMG_URL.'lecturer/zw/index/person1.mp4?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zw/index/person2.mp4?'.GET_RAND,
                ],
                'banner'    => [
                    [
                        'title'     => '第九届全国政协委员-李晓华先生',
                        'img'       => GET_IMG_URL.'lecturer/zw/index/slide1.jpg?'.GET_RAND
                    ],
                    [
                        'title'     => '共和国四大演说家之一-彭清一老师',
                        'img'       => GET_IMG_URL.'lecturer/zw/index/slide2.jpg?'.GET_RAND
                    ],
                    [
                        'title'     => '昆仑决创始人-姜华先生',
                        'img'       => GET_IMG_URL.'lecturer/zw/index/slide3.jpg?'.GET_RAND
                    ],
                    [
                        'title'     => '全国政协科教文卫体副主任-蔡冠深先生',
                        'img'       => GET_IMG_URL.'lecturer/zw/index/slide4.jpg?'.GET_RAND
                    ],
                    [
                        'title'     => '全球社会性企业家生态论坛创始人-姜岚昕先生',
                        'img'       => GET_IMG_URL.'lecturer/zw/index/slide5.jpg?'.GET_RAND
                    ],
                    [
                        'title'     => '世界著名投资家-吉姆-罗杰斯先生',
                        'img'       => GET_IMG_URL.'lecturer/zw/index/slide6.jpg?'.GET_RAND
                    ]

                ],
                'student'       => [
                    GET_IMG_URL.'lecturer/zw/index/stu1.jpg?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zw/index/stu2.jpg?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zw/index/stu3.jpg?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zw/index/stu4.jpg?'.GET_RAND,
                ],
                'code'          => GET_IMG_URL.'lecturer/zw/index/code.jpg?'.GET_RAND
            ],
        ];

        //返回内容
        return $data;
    }

}