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
            'log'           => GET_IMG_URL.'lecturer/daikuan/zhezhao/logo.png?'.GET_RAND,
            'banner'       => [
                GET_IMG_URL.'lecturer/daikuan/zhezhao/banner/1.png?'.GET_RAND,
                GET_IMG_URL.'lecturer/daikuan/zhezhao/banner/2.png?'.GET_RAND,
                GET_IMG_URL.'lecturer/daikuan/zhezhao/banner/3.png?'.GET_RAND,
            ],
            'info'      => [
                'title'     =>  '汉中汉邦汽车贸易有限责任公司',
                'industry'     =>  '浙江众泰汽车制造有限公司始建于2003年，是一家以汽车整车及发动机、模具、钣金件、变速器等汽车关键零部件的研发制造为核心业务和发展方向的现代化民营企业集团。具有国内外先进水平的冲压、焊装、涂装',
                'address'     =>  '芙蓉区芙蓉中路二段80号顺天财富大厦',
                'mapname'     =>  '汉中汉邦汽车贸易有限责任公司',
                'latitude'     =>  '28.1895100000',
                'longitude'     =>  '112.9868500000',
                'time'     =>  '09:00-21:00',
                'phone'     =>  '15111097623',
            ],
            'image'     => [
                GET_IMG_URL.'lecturer/daikuan/zhezhao/chanping/4.png?'.GET_RAND,
                GET_IMG_URL.'lecturer/daikuan/zhezhao/chanping/5.png?'.GET_RAND,
                GET_IMG_URL.'lecturer/daikuan/zhezhao/chanping/6.png?'.GET_RAND,
                GET_IMG_URL.'lecturer/daikuan/zhezhao/chanping/7.png?'.GET_RAND,
                GET_IMG_URL.'lecturer/daikuan/zhezhao/chanping/8.png?'.GET_RAND,
            ]
        ];

        //返回内容
        return $data;
    }

}