<?php
namespace app\api\provider;

/**
 *  卢煜 * 个人资料
 */
class Ly
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
            'name'          => '卢煜',
            'explain'       => '中国青年创业导师',
            'portrait'      => GET_IMG_URL.'lecturer/ly/portrait.png?'.GET_RAND,
            'video'         => [
                'mp4'       => GET_IMG_URL.'lecturer/ly/video.mp4?'.GET_RAND,
                'cover'     => GET_IMG_URL.'lecturer/ly/cover.jpg?'.GET_RAND,
                'content'   => [
                    '他曾经打工月收入只有300元',
                    '他曾经住过工地、睡过公园食不果腹...',
                    '他曾经被无数人嘲笑与打击',
                    '但是他知道销售是改变命运唯一的捷径！',
                    '于是他大量的学习销售、练习销售、钻研销售！',
                    '功夫不负有心人！',
                    '他从一个销售领域的小白到销售领域专家',
                    '再到销售领域大师级高手他只用了短短三年时间！',
                    '通过销售他的生命开始发生魔术般的改变'
                ]
            ],
            'experience'    => [
                [
                    'titile'    => '23岁',
                    'content'   => '好望角集团内训教练'
                ],
                [
                    'titile'    => '24岁',
                    'content'   => '巡回全中国做演讲'
                ],
                [
                    'titile'    => '25岁',
                    'content'   => '进公司7天就 可以上台演讲'
                ],
                [
                    'titile'    => '25岁',
                    'content'   => '全款买上奔驰轿车'
                ],
                [
                    'titile'    => '',
                    'content'   => '影响学员超过 20万人'
                ],
                [
                    '他此生梦想是通过',
                    '公众演说点亮一亿人生命！',
                    '帮助一亿人学会销售改变命运！',
                    '并协助恩师黄佰胜老师创立',
                    '千年学府，恩泽子孙后代一千年！',
                    '他就是被小伙伴誉为最有震撼力、最有能量、最有爱心、最有',
                    '责任感的实战超级演说家！'
                ]
            ],
            'picture'        => [
                GET_IMG_URL.'lecturer/ly/min1.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/ly/min2-1.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/ly/min3-1.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/ly/min4.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/ly/min5.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/ly/min6.jpg?'.GET_RAND
            ],
        ];
        //返回内容
        return $data;
    }

}