<?php
namespace app\api\provider;

/**
 *  张丽雅 * 个人资料
 */
class Zly
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
            'bg1'           => GET_IMG_URL.'lecturer/zly/index/bg1.png?'.GET_RAND,
            'name'          => '张丽雅',
            'explain'       => '金牌总代',
            'portrait'      => GET_IMG_URL.'lecturer/zly/index/portrait.jpg?'.GET_RAND,
            'phone'         => '',
            'title1'      =>[
                GET_IMG_URL.'lecturer/zly/index/title1.png?'.GET_RAND,
                '我要比以前努力10倍',
                '帮助30000位宝宝突破月收入过万',
                '10年的时间打造1000位亿万富翁',
                '10年的时间打造10000位百万富翁',
                '3年内帮助60位总代成为金牌总代月收入突破50万',
                '3年内帮助200位总代突破月收入10万',
                '我立志此生帮助10000位伙伴，买上属于自己的百万豪车，住上属于自己的别墅',
                '10年的时间打造100万人的团队',
                '10年的时间打造5000位千万富翁',
                '我立志3年内打造20万人的团队',
                '3年内帮助100位总代突破月收入30万',
                '我立志此生在羽悦本草10年的时间帮助100万人用上我们的产品让他们的家庭幸福美满'
            ],
            'team'          => [
                'img'           => GET_IMG_URL.'lecturer/zly/index/title2.jpg?'.GET_RAND,
                'txt'           => '张丽雅，从踏入微商一个月卖不出一盒货到现在月收入破70万培养了总代80人，其中月收入过50万的总代就有5人，50个总代月收入过万。在2016年年会被公司评为四大金牌总代，2017年亲自又培养了一个金牌总代！',
            ],
            'product'       => [
                'img'           => GET_IMG_URL.'lecturer/zly/index/title3.jpg?'.GET_RAND,
                'describe'      => '瘦身、养生、补肾、肩颈暖护,就找张丽雅！,您身边的养身专家！',
                'details'       => [
                    [
                        'name'      => '古方暖护包',
                        'img'       => GET_IMG_URL.'lecturer/zly/index/details1.jpg?'.GET_RAND
                    ],
                    [
                        'name'      => '古方纤秀包',
                        'img'       => GET_IMG_URL.'lecturer/zly/index/details1.jpg?'.GET_RAND
                    ],
                    [
                        'name'      => '古方养护包',
                        'img'       => GET_IMG_URL.'lecturer/zly/index/details1.jpg?'.GET_RAND
                    ],
                    [
                        'name'      => '肩颈暖护包',
                        'img'       => GET_IMG_URL.'lecturer/zly/index/details1.jpg?'.GET_RAND
                    ]
                ]
            ],
            'introduce'     => [
                'img'           => GET_IMG_URL.'lecturer/zly/index/title4.png?'.GET_RAND,
                'image'         => GET_IMG_URL.'lecturer/zly/index/bg2.png?'.GET_RAND,
                'title'         => '草本 • 健康 • 美丽',
                'detail'        => '羽悦本草健康科技股份有限公司是良兄国际集团的全资子公司，公司位于深圳，成立于2017年1月16日。羽悦本草健康科技有限公司是一家以植物草本养生保健为基础的健康养生产业公司，其业务涵盖生产、零售及分销现代化健康养生产品。 羽悦本草健康科技股份有限公司，是一家以植物草本和草本植物精华为核心，生产功能型食品、健康养生产品的高科技企业。作为一家草本植物多位一体的健康科技研发型企业，羽悦本草坚持质量和创新作为品牌经营的基础，着力研发健康产业高品质的产品，羽悦本草本着拓展全渠道营销的模式，线上建立遍布国内外的营销体系，线下大力发展各大渠道的门店，羽悦本草品牌在国内外市场上赢得了良好的美誉，成为同行业领域的优势品牌。
                        公司代表中国微商参加中韩两国微商研讨会；羽悦本草品牌成为“411移动电商节”联合发起人；“第二届微商春晚”的联合发起人；羽悦本草的产品入选为《CCTV央视网商城优选品牌》；荣获“2016十大重诚信品牌之一”。',
            ],
            'join'          => [
                'title'         => GET_IMG_URL.'lecturer/zly/index/title5.png?'.GET_RAND,
                'address'       => '深圳市罗湖区京基100大厦5303',
                'phone'         => '4008265533',
                'code'          => GET_IMG_URL.'lecturer/zly/index/code.png?'.GET_RAND
            ]
        ];

        //返回内容
        return $data;
    }

}