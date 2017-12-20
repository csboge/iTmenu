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
            'log'          => GET_IMG_URL.'lecturer/daikuan/logo.jpg?'.GET_RAND,
            'banner'       => [
                GET_IMG_URL.'lecturer/daikuan/zhezhao/banner/1.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/daikuan/zhezhao/banner/2.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/daikuan/zhezhao/banner/3.jpg?'.GET_RAND,
            ],
            'info'      => [
                'title'         =>  '长沙微粒贷款服务中心',
                'industry'      =>  '全款房，按揭房，全款车，按揭车，社保公积金，打卡工资，保单，微粒贷，大专学历，淘宝4颗心，长沙本地人，满足其中一项就可以联系我拿钱。',
                'address'       =>  '芙蓉区芙蓉中路二段80号顺天财富大厦',
                'mapname'       =>  '长沙微粒贷款服务中心',
                'latitude'      =>  '28.1895100000',
                'longitude'     =>  '112.9868500000',
                'time'          =>  '09:00-21:00',
                'phone'         =>  '15111097623',
            ],
            'image'     => [
                GET_IMG_URL.'lecturer/daikuan/zhezhao/chanping/4.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/daikuan/zhezhao/chanping/5.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/daikuan/zhezhao/chanping/6.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/daikuan/zhezhao/chanping/7.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/daikuan/zhezhao/chanping/8.jpg?'.GET_RAND,
            ],
            'tagging'       => '长沙伯格网络 －技术支持'
        ];

        //返回内容
        return $data;
    }

}