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
            'log'           => GET_IMG_URL.'lecturer/daikuan/logo.jpg'.GET_RAND,
            'banner'       => [
                'http://img.my-shop.cc/daikuan/1.jpg',
                'http://img.my-shop.cc/daikuan/4.jpg',
                'http://img.my-shop.cc/daikuan/7.jpg'
            ],
            'info'      => [
                'title'     =>  '长沙微粒贷款服务中心',
                'industry'     =>  '全款房，按揭房，全款车，按揭车，社保公积金，打卡工资，保单，微粒贷，大专学历，淘宝4颗心，长沙本地人，满足其中一项就可以联系我拿钱。',
                'address'     =>  '芙蓉区芙蓉中路二段80号顺天财富大厦',
                'mapname'     =>  '长沙微粒贷款服务中心',
                'latitude'     =>  '28.1895100000',
                'longitude'     =>  '112.9868500000',
                'time'     =>  '09:00-21:00',
                'phone'     =>  '15111097623',
            ],
            'image'     => [
                'http://img.my-shop.cc/daikuan/5.jpg',
                'http://img.my-shop.cc/daikuan/8.jpg',
                'http://img.my-shop.cc/daikuan/2.jpg',
                'http://img.my-shop.cc/daikuan/6.jpg',
                'http://img.my-shop.cc/daikuan/3.jpg'
            ]
        ];

        //返回内容
        return $data;
    }

}