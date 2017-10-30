<?php
namespace app\api\provider;

/**
 *  周伟 * 个人资料
 */
class ZwDetails
{

    public function __construct(){}
    

    /**
     * 获得信息内容
     *
     * @param   string   $id   建值
     *
     * @return  string 
     *
     */
    public function getUser($id)
    {
        $data = [
            1   => '',
            2   => [
                GET_IMG_URL.'lecturer/zly/zw/details/2/rx1.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/zly/zw/details/2/rx2.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/zly/zw/details/2/rx3.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/zly/zw/details/2/rx4.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/zly/zw/details/2/rx5.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/zly/zw/details/2/rx6.jpg?'.GET_RAND
            ],
            3   => '',
            4   => '',
            5   => [
                GET_IMG_URL.'lecturer/zly/zw/details/5/t1.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/zly/zw/details/5/t2.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/zly/zw/details/5/t3.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/zly/zw/details/5/t4.jpg?'.GET_RAND
            ],
            6   => [
                GET_IMG_URL.'lecturer/zly/zw/details/6/l1.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/zly/zw/details/6/l2.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/zly/zw/details/6/l3.jpg?'.GET_RAND
            ],
        ];

        //返回内容
        return $data[$id];
    }

}