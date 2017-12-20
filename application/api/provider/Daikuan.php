<?php
namespace app\api\provider;

/**
 *  贷款
 */
class Daikuan
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
            'log'          => GET_IMG_URL.'lecturer/daikuan/logo.png?'.GET_RAND,
            'banner'       => [
                GET_IMG_URL.'lecturer/daikuan/zhezhao/banner/1.png?'.GET_RAND,
                GET_IMG_URL.'lecturer/daikuan/zhezhao/banner/2.png?'.GET_RAND,
                GET_IMG_URL.'lecturer/daikuan/zhezhao/banner/3.png?'.GET_RAND,
            ],
            'info'      => [
                'title'         =>  '上汽大众汽车有限公司',
                'industry'      =>  'Teramont途昂搭载的8英寸MIB触摸屏娱乐及导航系统支持高级蓝牙、MirrorLink等多种连接方式。夜晚，Teramont途昂的环境氛围灯带4种颜色可调，打造不同风格的车内氛围，让个性氛围与快乐随行。自己的地盘自己做主，Teramont途昂拥有如此大的空间及丰富的配置，你准备怎么玩？更多精彩体验等你解锁。',
                'address'       =>  '芙蓉区芙蓉中路二段80号顺天财富大厦',
                'mapname'       =>  '上汽大众汽车有限公司',
                'latitude'      =>  '28.1895100000',
                'longitude'     =>  '112.9868500000',
                'time'          =>  '09:00-21:00',
                'phone'         =>  '15111097623',
            ],
            'image'     => [
                GET_IMG_URL.'lecturer/daikuan/zhezhao/chanping/4.png?'.GET_RAND,
                GET_IMG_URL.'lecturer/daikuan/zhezhao/chanping/5.png?'.GET_RAND,
                GET_IMG_URL.'lecturer/daikuan/zhezhao/chanping/6.png?'.GET_RAND,
                GET_IMG_URL.'lecturer/daikuan/zhezhao/chanping/7.png?'.GET_RAND,
                GET_IMG_URL.'lecturer/daikuan/zhezhao/chanping/8.png?'.GET_RAND,
            ],
            'tagging'       => '长沙伯格网络 －技术支持'
        ];

        //返回内容
        return $data;
    }

}