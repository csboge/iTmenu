<?php
namespace app\api\controller;

class Index
{
    public function index()
    {
        $data = ['name'=>'thinkphp','url'=>'thinkphp.cn'];
        return jsonData(1, 'ok', $data);
    }
}
