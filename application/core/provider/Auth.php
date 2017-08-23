<?php
namespace app\core\provider;

class Auth
{
    use \app\core\traits\ProviderFactory;

    public function __construct(){}


    /***
     * 获得授权代码
     * 
     * @return array
     */
    public function getAccessToken()
    {
        return input('get.access_token/s');
    }



    /***
     * 获得合法授权
     * 
     * @return array
     */
    public function setToken($userid, $session)
    {

         $redis = $this->redisFactory();

         /***
          * 生成授权码
          *
          */
         $access_token = md5(substr(md5($userid . date('Y-m-d')), 5));
         $expires_in   = 7200;


         //设置授权身份信息
         $jsondata     = json_encode($session);
         $redis->set('access_token:'.$access_token, $jsondata, $expires_in);

         return ['access_token'=>$access_token, 'expires_in'=>$expires_in];
    }



    /***
     * 获得授权信息
     * 
     * @return  array
     */
    public function session()
    {
        $redis             = $this->redisFactory();
        $access_token      = $this->getAccessToken();

        //身份信息
        $userinfo          = $redis->get('access_token:'.$access_token);
        if (!$userinfo) return false;

        //返回数据
        $session           = json_decode($userinfo, true);
        return !is_null($session) ? $session: false;
    }



    /***
     * 验证授权合法
     * 
     * @return void
     */
    public function check($request, $actions = [])
    {
        //当前请求信息
        $module     = $request->module();
        $action     = $request->action();
        $controller = $request->controller();

  
        //验证配置
        $isauth     = true;
        $public     = isset($actions['public']) ? $actions['public'] : [];
        $private    = isset($actions['private']) ? $actions['private'] : [];

        //验证开关
        $isauth     = (in_array($action, $public) || in_array('*', $public)) ? false : true;
        $isauth     = (!$isauth && !in_array($action, $private)) ? false : true;
        $isauth     = (!$isauth && !in_array('*', $private)) ? false : true;

        /*** 
         * 无需验证，直接返回
         *
         */
        if (!$isauth) return true;

        /***
         * 通过验证，直接返回
         */
         if ($this->session()) return true;


         /***
          * 根据每个控制器，不同方法，是否需要验证
          * 错误：抛出不同错误信息，错误代码，全局作用范围
          *
          */
          $code     = '-404';
          $message  = '授权无效';
            
          echo json_encode(['code'=>$code, 'message'=>$message]);
          exit;
    }
}