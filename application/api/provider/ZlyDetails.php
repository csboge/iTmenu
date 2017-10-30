<?php
namespace app\api\provider;

/**
 *  张丽雅 * 个人资料
 */
class ZlyDetails
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
            1   => [
                'banner'    => [
                    GET_IMG_URL.'lecturer/zly/details/menu1/1.png?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu1/2.png?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu1/3.png?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu1/4.png?'.GET_RAND,
                ],
                'content'   => [
                    GET_IMG_URL.'lecturer/zly/details/menu1/menu1_1.jpg?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu1/menu1_2.jpg?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu1/menu1_3.jpg?'.GET_RAND
                ]

            ],
            2   => [
                'banner'    => [
                    GET_IMG_URL.'lecturer/zly/details/menu2/1.png?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu2/2.png?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu2/3.png?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu2/4.png?'.GET_RAND,
                ],
                'content'   => [
                    GET_IMG_URL.'lecturer/zly/details/menu2/menu2_1.jpg?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu2/menu2_2.jpg?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu2/menu2_3.jpg?'.GET_RAND
                ]

            ],
            3   => [
                'banner'    => [
                    GET_IMG_URL.'lecturer/zly/details/menu3/1.png?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu3/2.png?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu3/3.png?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu3/4.png?'.GET_RAND,
                ],
                'content'   => [
                    GET_IMG_URL.'lecturer/zly/details/menu3/menu3_1.jpg?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu3/menu3_2.jpg?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu3/menu3_3.jpg?'.GET_RAND
                ]

            ],
            4   => [
                'banner'    => [
                    GET_IMG_URL.'lecturer/zly/details/menu4/1.png?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu4/2.png?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu4/3.png?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu4/4.png?'.GET_RAND,
                ],
                'content'   => [
                    GET_IMG_URL.'lecturer/zly/details/menu4/menu4_1.jpg?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu4/menu4_2.jpg?'.GET_RAND,
                    GET_IMG_URL.'lecturer/zly/details/menu4/menu4_3.jpg?'.GET_RAND
                ]

            ]
        ];

        //返回内容
        return $data[$id];
    }

}