<?php
namespace app\api\controller;


use think\Request;
use think\View;
use think\Db;
class Shopjoin
{
    private     $p_auth;

    /***
     * 公共 - 注入依赖
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
     * 商铺入驻 - 信息填写
     */
        

   public  function addinfo()
    {
         $data=array(
                'title'=>input('title'),
                'adress'=>input('adress'),
                'mobile'=>input('mobile'),
                'imgz' => input('image'),
                'imgs' => input('image'),
                'imgf' => input('image')

             ); 

    // 获取表单上传文件
    $files = request()->file('image');
    foreach($files as $file){
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
        if($info){
            // 成功上传后 获取上传信息
            // 输出 jpg
            echo $info->getExtension(); 
            // 输出 42a79759f284b767dfcb2a0197904287.jpg
            echo $info->getFilename(); 
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }    
    }
    // var_dump($info);
    $img_url = config('upload_path'). '/' . date('Ymd') . '/' . $info->getFilename();
    // var_dump($img_url);
    
     //判断手机号
            if(preg_match("/^1[34578]{1}\d{9}$/",$data['mobile'])){  
               echo "输入正确";  
            }else{  
                return jsonData(-1, '请输入正确的手机号码格式', null);
            }  
    //写入数据库
        $data['imgz'] =  $img_url;
        $data['imgs'] =  $img_url;
        $data['imgf'] =  $img_url;
    
        $shop = new \app\core\model\Shopjoin();
        $shop->getShopJoin($data);

        $str=$shop->getShopJoins($data);

        // var_dump($res);

        $view = new View();

        $view->assign('str',$str);  

        return $view->fetch();

    // var_dump($data);

    return jsonData(1, 'ok', null);
    }



    /***
     * 获得 - 菜谱菜品
     */
    function goods()
    {
        
        $data       = $this->p_auth->session();

        return jsonData(1, 'ok', $data);
    }


}