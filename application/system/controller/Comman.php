<?php
namespace app\system\controller;

use Symfony\Component\Yaml\Escaper;
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
            return jsonDataList(0,'未接收到数据',[]);
        }
        $res = Db::name($data['name'])->where('id',$data['id'])->setField($data['status'],$data['value']);
        if($res){
            return jsonDataList(1,'OK',[]);
        }else{
            return jsonDataList(0,'数据修改失败',[]);
        }
    }


    /***
     * 上传 -- 单个图片
     * @参数 file      图片
     */
    public function upload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = $_FILES['file'];
        $name = $file['name'];
        $type = strtolower(substr($name,strrpos($name,'.')+1)); //得到文件类型，并且都转化成小写
        $allow_type = array('jpg','jpeg','gif','png'); //定义允许上传的类型
        //判断文件类型是否被允许上传
        if(!in_array($type, $allow_type)){
            //如果不被允许，则直接停止程序运行
            return false;
        }
        //判断是否是通过HTTP POST上传的
        if(!is_uploaded_file($file['tmp_name'])){
            //如果不是通过HTTP POST上传的
            return false;
        }
        $url = 'picture'.DS.date('Ymd',time()).DS; //上传文件的存放路径
        $upload_path = ROOT_PATH . 'Uploads'.DS.'picture'.DS.date('Ymd',time()).DS; //上传文件的存放路径
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }//判断目录是否存在，不存在则创建
        //开始移动文件到相应的文件夹
        if(move_uploaded_file($file['tmp_name'],$upload_path.$name)){
            // 成功上传后 获取上传信息
            $data = [
                'path' => 'picture' . DS . $url.$name,
                'status' => 1,
                'create_time' => time()
            ];
            $res = Db::name('picture')->insertGetId($data);
            if($res){
                $post = [
                    'id' => $res,
                    'path' =>GET_IMG_URL.$url.$name,
                ];
                return jsonDataList(1,'OK',$post);
            }else {
                return jsonDataList(0, '数据添加失败', []);
            }
        }else{
            return jsonDataList(0,'上传失败');
        }

//        $file = input("param.file");
//        print_r($file);exit;
//        return jsonDataList(1,'数据上传失败',$file);
//        if(!$file){
//            return jsonDataList(0,'未接收到数据',[]);
//        }
//        // 移动到框架应用根目录/public/uploads/ 目录下
//        $info = $file
//            ->validate([
//                'size'=>145678,
//                'ext'=>'jpg,png,gif,jpeg',
//            ])
//            ->move(ROOT_PATH . 'Uploads' . DS . 'picture');
////        return jsonDataList(1,'数据上传失败',$info);
//        try{
//            // 成功上传后 获取上传信息
//            $data = [
//                'path' => 'picture' . DS . $info->getSaveName(),
//                'status' => 1,
//                'create_time' => time()
//            ];
//            $res = Db::name('picture')->insertGetId($data);
//            if($res){
//                $post = [
//                    'id' => $res,
//                    'path' =>GET_IMG_URL.'picture'. DS . $info->getSaveName(),
//                ];
//                return jsonDataList(1,'OK',$post);
//            }else{
//                return jsonDataList(0,'数据添加失败',[]);
//            }
//        }catch (\Exception $e){
//            return jsonDataList(0,'数据上传失败',[]);
//        }

    }

    /***
     * 分类 -- 模糊查询
     * @参数 shop_id      商户id
     * @参数 value        查询的值
     */
    public function category_search(){
        $like = input('param.');
        if(empty($like)){
            return jsonDataList(0,'未接收到数据',[]);
        }
        $map['shop_id'] = $like['shop_id'];
        $map['hd_status'] = 1;
        $map['name'] = array('like', "%{$like['value']}%");
//        print_r($map);exit;
        $data = Db::name('category')
            ->where($map)
            ->select();
        if($data){
            foreach ($data as &$value){
                $value['created'] = date('Y-m-d H:i:s', $value['created']);
                $value['updated'] = date('Y-m-d H:i:s', $value['updated']);
            }
            return jsonDataList(1,'OK',$data);
        }else{
            return jsonDataList(0,'查询失败',[]);
        }
    }

    /***
     * 菜品 -- 模糊查询
     * @参数 shop_id      商户id
     * @参数 value        查询的值
     */
    public function goods_search(){
        $like = input('param.');
        if(empty($like)){
            return jsonDataList(0,'未接收到数据',[]);
        }
        $map['shop_id'] = $like['shop_id'];
        $map['hd_status'] = 1;
        $map['title'] = array('like', "%{$like['value']}%");
        $data = Db::name('goods')
            ->where($map)
            ->select();
        $count = Db::name('goods')
            ->where($map)
            ->count();
        if($data){
            foreach ($data as &$value){
                $value['image'] = ImgUrl($value['image']);
                $value['created'] = date('Y-m-d H:i:s', $value['created']);
                $value['updated'] = date('Y-m-d H:i:s', $value['updated']);
                $value['attrs'] = json_decode($value['attrs'],true);
                $new_arr = multiToSingle($value['attrs']);          //降维处理
                if($new_arr[0] == ''){
                    $value['attrs'] = [];
                }
            }
            $res = [
                'list' => $data,
                'count' => $count
            ];
            return jsonDataList(1,'OK',$res);
        }else{
            return jsonDataList(0,'查询失败',[]);
        }
    }
}