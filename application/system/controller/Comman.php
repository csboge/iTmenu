<?php
namespace app\system\controller;

use think\Request;
use think\Db;
use think\File;

/**
 * 后台公共接口
 *
 *
 *
 */
class Comman
{

    use \app\core\traits\ProviderFactory;

    private     $p_auth;

    /***
     * 注入依赖
     *
     */
    public function __construct(
        Request                         $request,
        \app\core\provider\Auth         $p_auth
    )
    {
        //验证授权合法
        $p_auth->check($request, [
            'public' => ['*'],
            'private'=> []
        ]);

        //授权服务
        $this->p_auth   = $p_auth;
    }

    /***
     * 修改 -- 字段状态
     * @参数 name         数据库名
     * @参数 id           id
     * @参数 status       字段名
     * @参数 value        修改的值
     */
    public function get_status(){
        $data = input('param.');
        if(empty($data)){
            return json_encode(['code'=>0,'message'=>'未接收到数据','data'=>'','status'=>404]);
        }
        $res = Db::name($data['name'])->where('id',$data['id'])->setField($data['status'],$data['value']);
        if($res){
            return json_encode(['code'=>1,'message'=>'OK','data'=>'','status'=>200]);
        }else{
            return json_encode(['code'=>0,'message'=>'数据修改失败','data'=>'','status'=>202]);
        }
    }


    /***
     * 上传 -- 单个图片
     * @参数 file      图片
     */

    public function upload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        if(empty($file)){
            return json_encode(['code'=>0,'message'=>'未接收到数据','data'=>'','status'=>404]);
        }
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file
            ->validate([
                'size'=>145678,
                'ext'=>'jpg,png,gif,jpeg',
            ])
            ->move(ROOT_PATH . 'Uploads' . DS . 'picture');
        if($info){
            // 成功上传后 获取上传信息
            $data = [
                'path' => 'picture/'.$info->getSaveName(),
                'status' => 1,
                'create_time' => time()
            ];
            $res = Db::name('picture')->insertGetId($data);
            if($res){
                $post = [
                    'id' => $res,
                    'path' =>'http://img.my-shop.cc/picture/'.$info->getSaveName(),
                ];
                return json_encode(['code'=>1,'message'=>'OK','data'=>$post,'status'=>200]);
            }else{
                return json_encode(['code'=>0,'message'=>'数据添加失败','data'=>'','status'=>202]);
            }
        }else{
            // 上传失败获取错误信息
            return json_encode(['code'=>0,'message'=>'数据上传失败','data'=>'','status'=>2000]);
        }
    }

}