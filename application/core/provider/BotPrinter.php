<?php
namespace app\core\provider;

//中央控制系统授权
use app\core\model\TypeGoods;

define('USER', 'zhongmin@csboge.com');	//*必填*：飞鹅云后台注册账号
define('UKEY', 'kT79RSKyGm9JGye8');	    //*必填*: 飞鹅云注册账号后生成的UKEY

//以下参数不需要修改
define('IP','api.feieyun.cn');			//接口IP或域名
define('PORT',80);						//接口IP端口
define('PATH','/Api/Open/');			//接口路径
define('STIME', time());			    //公共参数，请求时间
define('SIG', sha1(USER.UKEY.STIME));   //公共参数，请求公钥

/**
 * 打印机方面 * 服务 * 操作方法
 *
 *
 *
 */
class BotPrinter
{

    //客户端 **打印机
    private $client;

    private $m_shop;

    public function __construct()
    {
        //初始化 **请求工具
        $this->client = new \app\core\provider\HttpClient(IP, PORT);

        //商户模型
        $this->m_shop = new \app\core\model\Shop();
    }
    

    
    /**
     * 排版 - 打印内容(测试接口)
     *
     *
     */
    function getWords($sn = ''){

        //$printer    = new \app\core\provider\BotPrinter();
        $sn         = ($sn) ? $sn : '217502439';

        $orderInfo = '<CB>电子菜谱</CB><BR>';
		$orderInfo .= '名称　　　　　 单价  数量 金额<BR>';
		$orderInfo .= '--------------------------------<BR>';
		$orderInfo .= '饭　　　　　 　10.0   10  10.0<BR>';
		$orderInfo .= '伯格  　　　　 10.0   10  10.0<BR>';
		$orderInfo .= '蛋炒饭　　　　 10.0   100 100.0<BR>';
		$orderInfo .= '伯格网络　　　 100.0  100 100.0<BR>';
		$orderInfo .= '西红柿炒饭　　 1000.0 1   100.0<BR>';
		$orderInfo .= '西红柿蛋炒饭　 100.0  100 100.0<BR>';
		$orderInfo .= '西红柿鸡蛋炒饭 15.0   1   15.0<BR>';
		$orderInfo .= '备注：加辣，多辣都行！<BR>';
		$orderInfo .= '--------------------------------<BR>';
		$orderInfo .= '合计：200.0元<BR>';
		$orderInfo .= '送货地点：长沙市万达广场C2-3508<BR>';
		$orderInfo .= '联系电话：0731-85056818<BR>';
		$orderInfo .= '订餐时间：'.date('Y-m-d H:i:s', time()).'<BR>';
		$orderInfo .= '<QR>http://www.csboge.com</QR>';//把二维码字符串用标签套上即可自动生成二维码

        $re = $this->wp_print($sn, $orderInfo, 1);


        $sn1         = ($sn) ? $sn : '217502439';
        $orderInfo = '<CB>单个菜品</CB><BR>';
		$orderInfo .= '名称　　　　　 单价  数量 金额<BR>';
		$orderInfo .= '--------------------------------<BR>';
		$orderInfo .= '辣椒宫保鸡丁   15.0   1   15.0    <BR>';
		$orderInfo .= '                                <BR>';
		$orderInfo .= '备注：加辣，多辣都行！<BR>';
		$orderInfo .= '--------------------------------<BR>';
        $re = $this->wp_print($sn, $orderInfo, 1);

    }

    function getWords_one($sn = ''){

        //$printer    = new \app\core\provider\BotPrinter();
        $sn         = ($sn) ? $sn : '217502439';

        $orderInfo = '<B>外三</B>               压台单<BR>';
        $orderInfo .= '<BR>';
        $orderInfo .= '8-12 13:46     点单一       2人<BR>';
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '辣鸡火锅　　　　　 　       1份<BR>';
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '口味脆莴笋　　　　　 　     1份<BR>';
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '餐纸巾　　　　　 　         1盒<BR>';
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '餐位费　　　　　 　         2位<BR>';
        $orderInfo .= '--------------------------------<BR>';
        $re = $this->wp_print($sn, $orderInfo, 1);

    }



    /**
     * 排版 - 打印内容(碎片接口)
     *
     *
     */
    function getWordsChip($sn = ''){

        $sn1         = ($sn) ? $sn : '217502992';
        $sn2         = ($sn) ? $sn : '217502989';
        $sn3         = ($sn) ? $sn : '217502995';
        $sn4         = ($sn) ? $sn : '217502996';
        $sn5         = ($sn) ? $sn : '217502998';
        $orderInfo = '<CB>单个菜品</CB><BR>';
		$orderInfo .= '名称　　　　　 单价  数量 金额<BR>';
		$orderInfo .= '--------------------------------<BR>';
		$orderInfo .= '辣椒宫保鸡丁   15.0   1   15.0    <BR>';
		$orderInfo .= '                                <BR>';
		$orderInfo .= '备注：加辣，多辣都行！<BR>';
		$orderInfo .= '--------------------------------<BR>';
        $re = $this->wp_print($sn1, $orderInfo, 1);
        //$re = $this->wp_print($sn2, $orderInfo, 1);
        //$re = $this->wp_print($sn3, $orderInfo, 1);
        //$re = $this->wp_print($sn4, $orderInfo, 1);
        $this->getWords($sn1);

        $orderInfo = '<CB>单个菜品</CB><BR>';
		$orderInfo .= '名称　　　　　 单价  数量 金额<BR>';
		$orderInfo .= '--------------------------------<BR>';
		$orderInfo .= '西红柿鸡蛋炒饭 15.0   1   15.0    <BR>';
		$orderInfo .= '                                <BR>';
		$orderInfo .= '备注：加辣，多辣都行！<BR>';
		$orderInfo .= '--------------------------------<BR>';

        $re = $this->wp_print($sn2, $orderInfo, 1);
        //$re = $this->wp_print($sn1, $orderInfo, 1);
        //$re = $this->wp_print($sn3, $orderInfo, 1);
        //$re = $this->wp_print($sn4, $orderInfo, 1);
        $this->getWords($sn2);

        $orderInfo = '<CB>单个菜品</CB><BR>';
		$orderInfo .= '名称　　　　　 单价  数量 金额<BR>';
		$orderInfo .= '--------------------------------<BR>';
		$orderInfo .= '白辣椒炒花猪肉 15.0   1   15.0    <BR>';
		$orderInfo .= '                                <BR>';
		$orderInfo .= '备注：加辣，多辣都行！<BR>';
		$orderInfo .= '--------------------------------<BR>';

        //$re = $this->wp_print($sn, $orderInfo, 1);
        //$re = $this->wp_print($sn1, $orderInfo, 1);
        $re = $this->wp_print($sn3, $orderInfo, 1);
        //$re = $this->wp_print($sn4, $orderInfo, 1);
        $this->getWords($sn3);


        $orderInfo = '<CB>单个菜品</CB><BR>';
		$orderInfo .= '名称　　　　　 单价  数量 金额<BR>';
		$orderInfo .= '--------------------------------<BR>';
		$orderInfo .= '酸辣鸡珍饭    15.0   1   15.0    <BR>';
		$orderInfo .= '                                <BR>';
		$orderInfo .= '备注：加辣，多辣都行！<BR>';
		$orderInfo .= '--------------------------------<BR>';
        //$re = $this->wp_print($sn, $orderInfo, 1);
        //$re = $this->wp_print($sn1, $orderInfo, 1);
        //$re = $this->wp_print($sn2, $orderInfo, 1);
        $re = $this->wp_print($sn4, $orderInfo, 1);
        $this->getWords($sn4);


        $orderInfo = '<CB>单个菜品</CB><BR>';
		$orderInfo .= '名称　　　　　 单价  数量 金额<BR>';
		$orderInfo .= '--------------------------------<BR>';
		$orderInfo .= '台湾经典卤肉饭 15.0   1   15.0    <BR>';
		$orderInfo .= '                                <BR>';
		$orderInfo .= '备注：加辣，多辣都行！<BR>';
		$orderInfo .= '--------------------------------<BR>';
        //$re = $this->wp_print($sn, $orderInfo, 1);
        //$re = $this->wp_print($sn1, $orderInfo, 1);
        //$re = $this->wp_print($sn2, $orderInfo, 1);
        //$re = $this->wp_print($sn3, $orderInfo, 1);
        $re = $this->wp_print($sn5, $orderInfo, 1);
        $this->getWords($sn5);


    }



    /**
     * 排版 - 打印内容(真实接口)
     *
     *
     */
    function printOrderInfo($order_info, $post_data){
        header("Content-type: text/html; charset=utf-8");
        //$printer    = new \app\core\provider\BotPrinter();

        $arr = json_decode($order_info['goods_list'], true);

        $youhui     = $order_info['coupon_price']+$order_info['first_money']+$order_info['offset_money'];

        $shop       = $this->m_shop->getShop($order_info['shop_id']);                   //查询商户信息

        $sn         = $shop['printer'];
        foreach ($arr as &$value){
            $value['extras'] = $value['price']*$value['num'];
        }
        $printe = json_decode($shop['printer_list'],true);

        if($shop['switch'] == 0)
        {
            //不分类整单小字体
            $this->printer_one($order_info,$arr,$sn);
            foreach ($printe as $prin)
            {
                if($prin['template'] == 1)
                {
                    $suen = $prin['number'];
                    $this->printer_five($order_info,$arr,$suen);
                }
            }
        }
        elseif ($shop['switch'] == 1)
        {
            //不分类整单大字体
            $this->printer_two($order_info,$arr,$sn);
        }
        elseif ($shop['switch'] == 2)
        {
            //分类整单小字体
            $this->printer_one($order_info,$arr,$sn);
        }
        elseif ($shop['switch'] == 3)
        {
            //分类整单大字体
            $this->printer_two($order_info,$arr,$sn,1);

            foreach ($arr as $item)
            {
                if($item['is_canju'] == 0)
                {
                    if($printe)
                    {
                        foreach ($printe as $prin)
                        {
                            if($item['type_id'] ==  $prin['template'])
                            {
                                $suen = $prin['number'];
                                //分单打印

                                $this->printer_three($order_info,$item,$suen);
                            }
                        }
                    }
                }
            }

        }
    }

    /**
     * 对菜品进行分类处理
     * @param $arr
     * @return array
     */
    public function fenlei($arr){
        $info = [];
        foreach($arr as $item){
            if($item['is_canju'] == 0){
                if($item['type_id'] == 1){
                    $info['zhon'][] = $item;
                }
                if($item['type_id'] == 2){
                    $info['xi'][] = $item;
                }
                if($item['type_id'] == 3){
                    $info['yin'][] = $item;
                }
                if($item['type_id'] == 4){
                    $info['jiu'][] = $item;
                }
                if($item['type_id'] == 5){
                    $info['bao'][] = $item;
                }
            }

        }
        return $info;
    }

    /**
     * 打印机模板一号
     *
     * 整单小字体
     */
    public function printer_one($order_info,$arr,$sn){
        $orderInfo = '<CB>电子菜谱</CB><BR>';
        $orderInfo .= '名称　　　　　 单价  数量 金额<BR>';
        $orderInfo .= '--------------------------------<BR>';
        foreach($arr as $item){
            $length = strlen($item['name']);
//            $length_price = strlen($item['price']);
//            print_r($length_price);exit;
            if($length <= 18){
                $length_cai = strlen($item['name']);
                $len_cai = mb_strlen($item['name'],'utf-8');
//                print_r($length_cai); echo  "<br>";
//                print_r($len_cai);exit;
                $a = (8-$len_cai)*2;
                $b = $length_cai+$a;
                $item['name'] = str_pad($item['name'],$b);
                $orderInfo .= $item['name'].str_pad($item['price'],6).str_pad($item['num'],6).$item['extras']."<BR>";
            }else{
                $name_a = mb_substr($item['name'],0,6,'utf-8');
                $length = strlen($name_a);
                $len = mb_strlen($name_a,'utf-8');
                $a = (8-$len)*2;
                $b = $length+$a;
                $name_a = str_pad($name_a,$b);
                $name_b = mb_substr($item['name'],6,100,'utf-8');
                $orderInfo .= $name_a.str_pad($item['price'],6).str_pad($item['num'],6).$item['extras']."<BR>";
                $orderInfo .= $name_b."<BR>";
            }
        }
        if(!empty($order_info['message'])){
            $orderInfo .= '--------------------------------<BR>';
            $orderInfo .= '备注：'.$order_info['message'].'<BR>';
        }
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '桌位：'.$order_info['desk_sn'].'<BR>';
        $orderInfo .= '支付：'.$order_info['pay_price'].'元<BR>';
        $orderInfo .= '红包：'.$order_info['mode_money'].'元<BR>';
        $orderInfo .= '下单时间：'.date('Y-m-d H:i:s',$order_info['created']).'<BR>';

//		$orderInfo .= '地址：'.$shop['adress'].'<BR>';
//		$orderInfo .= '联系电话：'.$shop['mobile'].'<BR>';
//		$orderInfo .= '座机电话：'.$shop['tel'].'<BR>';
//		$orderInfo .= '<QR>http://www.csboge.com</QR>';//把二维码字符串用标签套上即可自动生成二维码
        $re = $this->wp_print($sn, $orderInfo, 1);
    }


    /**
     * 打印机模板二号
     *
     * 整单大字体
     */
    public function printer_two($order_info,$arr,$sn,$is_price = false){

        $orderInfo = '<CB>电子菜谱</CB><BR>';
        if($is_price == true){
            $orderInfo .= '名称　　　　　  数量      价格<BR>';
        }else{
            $orderInfo .= '名称　　　　　           数量<BR>';
        }
        $orderInfo .= '--------------------------------<BR>';
        foreach($arr as $item){
            $length = strlen($item['name']);

            if($length <= 18){
                $length_cai = strlen($item['name']);
                $len_cai = mb_strlen($item['name'],'utf-8');

                $a = (6-$len_cai)*2;
                $b = $length_cai+$a;
                $item['name'] = str_pad($item['name'],$b);
                if($is_price == true){
                    $orderInfo .= "<B>".$item['name'].$item['num'].'份   '.$item['count_price'].'元'."</B><BR>";

                }else{
                    $orderInfo .= "<B>".$item['name'].$item['num'].'份'."</B><BR>";

                }
            }else{
                $name_a = mb_substr($item['name'],0,4,'utf-8');
                $length = strlen($name_a);
                $len = mb_strlen($name_a,'utf-8');

                $a = (6-$len)*2;
                $b = $length+$a;

                $name_a = str_pad($name_a,$b);
                $name_b = mb_substr($item['name'],4,100,'utf-8');
                if($is_price == true){
                    $orderInfo .= "<B>".$name_a.$item['num'].'份   '.$item['count_price'].'元'."</B><BR>";
                    $orderInfo .= "<B>".$name_b."</B><BR>";
                }else{
                    $orderInfo .= "<B>".$name_a.$item['num'].'份'."</B><BR>";
                    $orderInfo .= "<B>".$name_b."</B><BR>";
                }
            }
        }
        if(!empty($order_info['message'])){
            $orderInfo .= '--------------------------------<BR>';
            $orderInfo .= '<B>备注：'.$order_info['message'].'</B><BR>';
        }
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '桌位：'.$order_info['desk_sn'].'<BR>';
        $orderInfo .= '支付：'.$order_info['pay_price'].'元<BR>';
        $orderInfo .= '下单时间：'.date('Y-m-d H:i:s',$order_info['created']).'<BR>';

//		$orderInfo .= '地址：'.$shop['adress'].'<BR>';
//		$orderInfo .= '联系电话：'.$shop['mobile'].'<BR>';
//		$orderInfo .= '座机电话：'.$shop['tel'].'<BR>';
//		$orderInfo .= '<QR>http://www.csboge.com</QR>';//把二维码字符串用标签套上即可自动生成二维码
        $re = $this->wp_print($sn, $orderInfo, 1);
    }


    /**
     * 打印机模板三号
     *
     * 分单打印
     */
    public function printer_three($order_info,$arr,$sn){

        $typegoods = new TypeGoods();
        $name = $typegoods->typeList($arr['type_id']);
        $orderInfo = '<CB>电子菜谱('.$name.')</CB><BR>';
        $orderInfo .= '名称　　　　　           数量<BR>';
        $orderInfo .= '--------------------------------<BR>';
        $length = strlen($arr['name']);
        if($length <= 18){
            $length_cai = strlen($arr['name']);
            $len_cai = mb_strlen($arr['name'],'utf-8');
            $a = (6-$len_cai)*2;
            $b = $length_cai+$a;
            $arr['name'] = str_pad($arr['name'],$b);
            $orderInfo .= "<B>".$arr['name'].$arr['num'].'份'."</B><BR>";
        }else{
            $name_a = mb_substr($arr['name'],0,4,'utf-8');
            $length = strlen($name_a);
            $len = mb_strlen($name_a,'utf-8');
            $a = (6-$len)*2;
            $b = $length+$a;
            $name_a = str_pad($name_a,$b);
            $name_b = mb_substr($arr['name'],4,100,'utf-8');
            $orderInfo .= "<B>".$name_a.$arr['num'].'份'."</B><BR>";
            $orderInfo .= "<B>".$name_b."</B><BR>";
        }
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '桌位：'.$order_info['desk_sn'].'<BR>';
        $orderInfo .= '下单时间：'.date('Y-m-d H:i:s',$order_info['created']).'<BR>';
        $re = $this->wp_print($sn, $orderInfo, 1);
    }

    /**
     * 打印机模板四号
     *
     * 分机整单大字体
     */
    public function printer_four($order_info,$arr,$sn,$type){
        $typegoods = new TypeGoods();
        $name = $typegoods->typeList($type);
        $orderInfo = '<CB>电子菜谱('.$name.')</CB><BR>';
        $orderInfo .= '名称　　　　　           数量<BR>';
        $orderInfo .= '--------------------------------<BR>';
        foreach($arr as $item){
//            print_r($item);exit;
            $length = strlen($item['name']);
//            $length_price = strlen($item['price']);
            if($length <= 18){
                $length_cai = strlen($item['name']);
                $len_cai = mb_strlen($item['name'],'utf-8');
//                print_r($length_cai); echo  "<br>";
//                print_r($len_cai);exit;
                $a = (6-$len_cai)*2;
                $b = $length_cai+$a;
                $item['name'] = str_pad($item['name'],$b);
                $orderInfo .= "<B>".$item['name'].$item['num'].'份'."</B><BR>";
            }else{
                $name_a = mb_substr($item['name'],0,4,'utf-8');
                $length = strlen($name_a);
                $len = mb_strlen($name_a,'utf-8');
                $a = (6-$len)*2;
                $b = $length+$a;
                $name_a = str_pad($name_a,$b);
                $name_b = mb_substr($item['name'],4,100,'utf-8');
                $orderInfo .= "<B>".$name_a.$item['num'].'份'."</B><BR>";
                $orderInfo .= "<B>".$name_b."</B><BR>";
            }
        }
        if(!empty($order_info['message'])){
            $orderInfo .= '--------------------------------<BR>';
            $orderInfo .= '<B>备注：'.$order_info['message'].'</B><BR>';
        }
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '桌位：'.$order_info['desk_sn'].'<BR>';
        $orderInfo .= '下单时间：'.date('Y-m-d H:i:s',$order_info['created']).'<BR>';
//        print_r($orderInfo);exit;
//		$orderInfo .= '地址：'.$shop['adress'].'<BR>';
//		$orderInfo .= '联系电话：'.$shop['mobile'].'<BR>';
//		$orderInfo .= '座机电话：'.$shop['tel'].'<BR>';
//		$orderInfo .= '<QR>http://www.csboge.com</QR>';//把二维码字符串用标签套上即可自动生成二维码
        $re = $this->wp_print($sn, $orderInfo, 1);
    }

    /**
     * 打印机模板五号
     *
     * 分机整单小字体
     */
    public function printer_five($order_info,$arr,$sn,$type=''){
//        $typegoods = new TypeGoods();
//        $name = $typegoods->typeList($type);

        $orderInfo = '<CB>电子菜谱（厨房）</CB><BR>';
        $orderInfo .= '名称　　　　　           数量<BR>';
        $orderInfo .= '--------------------------------<BR>';
        foreach($arr as $item){
//            print_r($item);exit;
            $length = strlen($item['name']);
//            $length_price = strlen($item['price']);
            if($length <= 18){
                $length_cai = strlen($item['name']);
                $len_cai = mb_strlen($item['name'],'utf-8');
//                print_r($length_cai); echo  "<br>";
//                print_r($len_cai);exit;
                $a = (6-$len_cai)*2;
                $b = $length_cai+$a;
                $item['name'] = str_pad($item['name'],$b);
                $orderInfo .= $item['name'].$item['num'].'份'."<BR>";
            }else{
                $name_a = mb_substr($item['name'],0,4,'utf-8');
                $length = strlen($name_a);
                $len = mb_strlen($name_a,'utf-8');
                $a = (6-$len)*2;
                $b = $length+$a;
                $name_a = str_pad($name_a,$b);
                $name_b = mb_substr($item['name'],4,100,'utf-8');
                $orderInfo .= $name_a.$item['num'].'份'."<BR>";
                $orderInfo .= $name_b."<BR>";
            }
        }
        if(!empty($order_info['message'])){
            $orderInfo .= '--------------------------------<BR>';
            $orderInfo .= '备注：'.$order_info['message'].'<BR>';
        }
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '桌位：'.$order_info['desk_sn'].'<BR>';
        $orderInfo .= '下单时间：'.date('Y-m-d H:i:s',$order_info['created']).'<BR>';
//        print_r($orderInfo);exit;
//		$orderInfo .= '地址：'.$shop['adress'].'<BR>';
//		$orderInfo .= '联系电话：'.$shop['mobile'].'<BR>';
//		$orderInfo .= '座机电话：'.$shop['tel'].'<BR>';
//		$orderInfo .= '<QR>http://www.csboge.com</QR>';//把二维码字符串用标签套上即可自动生成二维码
        $re = $this->wp_print($sn, $orderInfo, 1);
    }




    /**
     * 添加打印机
     *
     * 打印机编号(必填) # 打印机识别码(必填) # 备注名称(选填) # 流量卡号码(选填)
     * $snlist = "sn1#key1#remark1#carnum1\nsn2#key2#remark2#carnum2";
     *
     */
    function addprinter($snlist){
        
            $content = array(			
                'user'=>USER,
                'stime'=>STIME,
                'sig'=>SIG,
                'apiname'=>'Open_printerAddlist',

                'printerContent'=>$snlist
            );
            
        //$client = new HttpClient(IP,PORT);
        if(!$this->client->post(PATH,$content)){
            return false;
        }
        else{
            return $this->client->getContent();
        }
    }


    /*
     *  方法1
     *  拼凑订单内容时可参考如下格式
     *  根据打印纸张的宽度，自行调整内容的格式，可参考下面的样例格式
     */
    function wp_print($printer_sn,$orderInfo,$times){
        
            $content = array(			
                'user'=>USER,
                'stime'=>STIME,
                'sig'=>SIG,
                'apiname'=>'Open_printMsg',

                'sn'=>$printer_sn,
                'content'=>$orderInfo,
                'times'=>$times//打印次数
            );
            
        //$client = new HttpClient(IP,PORT);
        if(!$this->client->post(PATH,$content)){
            return false;
        }
        else{
            //服务器返回的JSON字符串，建议要当做日志记录起来
            return $this->client->getContent();
        }
        
    }





    /*
     *  方法2
        根据订单索引,去查询订单是否打印成功,订单索引由方法1返回
     */
    function queryOrderState($index){
            $msgInfo = array(
                'user'=>USER,
                'stime'=>STIME,
                'sig'=>SIG,	 
                'apiname'=>'Open_queryOrderState',
                
                'orderid'=>$index
            );
        
        //$client = new HttpClient(IP,PORT);
        if(!$this->client->post(PATH,$msgInfo)){
            return false;
        }
        else{
            $result = $this->client->getContent();
            return $result;
        }
        
    }




    /*
     *  方法3
        查询指定打印机某天的订单详情
     */
    function queryOrderInfoByDate($printer_sn,$date){
            $msgInfo = array(
                'user'=>USER,
                'stime'=>STIME,
                'sig'=>SIG,			
                'apiname'=>'Open_queryOrderInfoByDate',
                
                'sn'=>$printer_sn,
                'date'=>$date
            );
        
        //$client = new HttpClient(IP,PORT);
        if(!$this->client->post(PATH,$msgInfo)){ 
            return false;
        }
        else{
            $result = $this->client->getContent();
            return $result;
        }
        
    }



    /*
     *  方法4
        查询打印机的状态
     */
    function queryPrinterStatus($printer_sn){
            
            $msgInfo = array(
                'user'=>USER,
                'stime'=>STIME,
                'sig'=>SIG,	
                'apiname'=>'Open_queryPrinterStatus',
                
                'sn'=>$printer_sn
            );
        
        //$client = new HttpClient(IP,PORT);
        if(!$this->client->post(PATH,$msgInfo)){
            return false;
        }
        else{
            $result = $this->client->getContent();
            return $result;
        }
    }

}






//===========添加打印机接口（支持批量）=============

		//***接口返回值说明***
		//正确例子：{"msg":"ok","ret":0,"data":{"ok":["sn#key#remark#carnum","316500011#abcdefgh#快餐前台"],"no":["316500012#abcdefgh#快餐前台#13688889999  （错误：识别码不正确）"]},"serverExecutedTime":3}
		//错误：{"msg":"参数错误 : 该帐号未注册.","ret":-2,"data":null,"serverExecutedTime":37}
		
		//打开注释可测试
		//提示：打印机编号(必填) # 打印机识别码(必填) # 备注名称(选填) # 流量卡号码(选填)，多台打印机请换行（\n）添加新打印机信息，每次最多100行(台)。
		//$snlist = "sn1#key1#remark1#carnum1\nsn2#key2#remark2#carnum2";
		//addprinter($snlist);
		





//==================方法1.打印订单==================
		//***接口返回值说明***
		//正确例子：{"msg":"ok","ret":0,"data":"316500004_20160823165104_1853029628","serverExecutedTime":6}
		//错误：{"msg":"错误信息.","ret":非零错误码,"data":null,"serverExecutedTime":5}
				
		
		//标签说明：
		//单标签:
		//"<BR>"为换行,"<CUT>"为切刀指令(主动切纸,仅限切刀打印机使用才有效果)
		//"<LOGO>"为打印LOGO指令(前提是预先在机器内置LOGO图片),"<PLUGIN>"为钱箱或者外置音响指令
		//成对标签：
		//"<CB></CB>"为居中放大一倍,"<B></B>"为放大一倍,"<C></C>"为居中,<L></L>字体变高一倍
		//<W></W>字体变宽一倍,"<QR></QR>"为二维码,"<BOLD></BOLD>"为字体加粗,"<RIGHT></RIGHT>"为右对齐
	    //拼凑订单内容时可参考如下格式
		//根据打印纸张的宽度，自行调整内容的格式，可参考下面的样例格式

		$orderInfo = '<CB>电子菜谱</CB><BR>';
		$orderInfo .= '名称　　　　　 单价  数量 金额<BR>';
		$orderInfo .= '--------------------------------<BR>';
		$orderInfo .= '饭　　　　　 　10.0   10  10.0<BR>';
		$orderInfo .= '伯格饭　　　　 10.0   10  10.0<BR>';
		$orderInfo .= '蛋炒饭　　　　 10.0   100 100.0<BR>';
		$orderInfo .= '伯格网络　　　 100.0  100 100.0<BR>';
		$orderInfo .= '西红柿炒饭　　 1000.0 1   100.0<BR>';
		$orderInfo .= '西红柿蛋炒饭　 100.0  100 100.0<BR>';
		$orderInfo .= '西红柿鸡蛋炒饭 15.0   1   15.0<BR>';
		$orderInfo .= '备注：加辣，伯格网络专用辣椒<BR>';
		$orderInfo .= '--------------------------------<BR>';
		$orderInfo .= '合计：100.0元<BR>';
		$orderInfo .= '送货地点：长沙市万达广场C2-3508<BR>';
		$orderInfo .= '联系电话：0731-85056818<BR>';
		$orderInfo .= '订餐时间：'.date('Y-m-d', time()).' 08:08:08<BR>';
		$orderInfo .= '<QR>http://www.csboge.com</QR>';//把二维码字符串用标签套上即可自动生成二维码
		

		
		//打开注释可测试
		//wp_print(SN,$orderInfo,1);
		

		
//===========方法2.查询某订单是否打印成功=============
		//***接口返回值说明***
		//正确例子：
		//已打印：{"msg":"ok","ret":0,"data":true,"serverExecutedTime":6}
		//未打印：{"msg":"ok","ret":0,"data":false,"serverExecutedTime":6}
		
		//打开注释可测试
		//$orderid = "xxxxxxxx_xxxxxxxxxx_xxxxxxxx";//订单ID，从方法1返回值中获取
		//queryOrderState($orderid);
		

		
	
//===========方法3.查询指定打印机某天的订单详情============
		//***接口返回值说明***
		//正确例子：{"msg":"ok","ret":0,"data":{"print":6,"waiting":1},"serverExecutedTime":9}
		
		//打开注释可测试
		//$date = "2017-04-02";//注意时间格式为"yyyy-MM-dd",如2016-08-27
		//queryOrderInfoByDate(SN,$date);
		



//===========方法4.查询打印机的状态==========================
		//***接口返回值说明***
		//正确例子：
		//{"msg":"ok","ret":0,"data":"离线","serverExecutedTime":9}
		//{"msg":"ok","ret":0,"data":"在线，工作状态正常","serverExecutedTime":9}
		//{"msg":"ok","ret":0,"data":"在线，工作状态不正常","serverExecutedTime":9}
		
		//打开注释可测试
		//queryPrinterStatus(SN);