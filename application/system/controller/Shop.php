<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/22
 * Time: 17:55
 */

namespace app\system\controller;

use think\Request;
use think\Db;


class Shop
{
    use \app\core\traits\ProviderFactory;

    private     $p_auth;

    /***
     * 注入依赖
     *
     */
    public function __construct(
        Request                         $request,
        \app\core\provider\Auth         $p_auth,
        \app\core\model\User            $m_user,
        \app\core\model\Shop            $m_shop,
        \app\core\model\Orders          $m_orders
    )
    {
        //验证授权合法
        $p_auth->check($request, [
            'public' => ['*'],
            'private'=> []
        ]);

        //授权服务
        $this->p_auth           = $p_auth;

        //用户模型
        $this->m_user           = $m_user;

        //商铺模型
        $this->m_shop           = $m_shop;

        //订单模型
        $this->m_orders         = $m_orders;
    }


    /**
     * 查看商铺信息
     * @return array
     */
    public function shopInfo(){
        $shop_id     = $this->p_auth->getShopId();
        if(!$shop_id){
            return jsonDataList(0,'未接收到数据',[]);
        }

        $shop = $this->m_shop->getSystemShop($shop_id);
        $shop['logo_id']    = $shop['logo'];
        $shop['logo'] = ImgUrl($shop['logo']);
        if($shop['spread']){
            $shop['spread'] = GET_VIDEO_URL.$shop['spread'];
        }
        $shop['video'] = GET_VIDEO_URL.$shop['video'];
        return jsonDataList(1,'OK',$shop);

    }

    /**
     * 修改商铺信息
     * @return array
     */
    public function updateShop(){
        $data = input('param.');
        if(empty($data)){
            return jsonDataList(0,'未接收到数据',[]);
        }
        $data['id'] = $data['shop_id'];
        unset($data['shop_id']);
        $data['updated'] = time();
        $res = $this->m_shop->where('id',$data['id'])->update($data);
        if($res){
            return jsonDataList(1,'OK',[]);
        }else{
            return jsonDataList(0,'数据修改失败',[]);
        }
    }


    /**
     * 音频上传
     * @return array
     */
    public function getAudio()
    {
        $data = request()->file('audio');

        $data = upload_videos($data);

        if($data){
            $arr = $this->m_shop->where('id',$shop_id)->update(['video'=>$data]);
            if($arr){
                return jsonDataList(1,'OK',GET_VIDEO_URL.$data);
            }else{
                return jsonDataList(0,'数据修改失败',[]);
            }
        }else{
            return jsonDataList(-1,'数据修改失败',[]);
        }

    }

}