<?php
namespace app\api\provider;

/**
 *  周莉 * 个人资料
 */
class ZlDetails
{

    public function __construct(){}
    

    /**
     * 获得信息内容
     *
     * @param   string   $id    建值
     *
     * @return  string 
     *
     */
    public function getUser($id)
    {
        $data = [
            1   => [
                GET_IMG_URL.'lecturer/details/tc3_paf1.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_paf2.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_paf3.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_paf4.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_paf5.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_paf6.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_paf7.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_paf8.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_paf9.jpg?'.GET_RAND
            ],
            2   => [
                GET_IMG_URL.'lecturer/details/tc3_xy1.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_xy2.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_xy3.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_xy4.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_xy5.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_xy6.jpg?'.GET_RAND
            ],
            3   => [
                GET_IMG_URL.'lecturer/details/tc3_et1.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_et2.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_et3.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_et4.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_et5.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_et6.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_et7.jpg?'.GET_RAND
            ],
            4   => [
                GET_IMG_URL.'lecturer/details/tc3_pa1.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_pa2.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_pa3.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_pa4.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_pa5.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_pa6.jpg?'.GET_RAND,
                GET_IMG_URL.'lecturer/details/tc3_pa7.jpg?'.GET_RAND
            ]
        ];
        //返回内容
        return $data[$id];
    }

}