<?php
namespace app\core\traits;

//数据模型 *公共接口
trait InterfaceModel
{
    protected $primary = 'id';
    protected function getPrimary()
    {
        /**
         * 后期可能存在:
         *
         * 1, 模型使用了其他主键名称
         * 2, 修改本方法实现覆盖即可
         *
         */
        return $this->primary;
    }


    /**
     * 设置数据表 [数据分表工具方法]
     *
     * @tabname  string   数据表
     * @prefix   string   表前缀 [默认读取config]
     * 
     * @return   $this
     *
     *
     * 实现思路：
     *
     * 1, 各模型必须实现方法 dynamicHash() 
     * 2, 根据业务参数，计算出 [表名]
     * 3, 指定表信息 $this->setTable($tabname, $prefix)
     *
     */
    private function setTable($tabname, $prefix = '')
    {
        $fix    = ($prefix == '') ? config('database.prefix') : $prefix;
        $this->db()->setTable($fix . $tabname);

        return $this;
    }


    /**
     * 模型分表计算
     *
     * @params   array   业务参数
     * 
     * @return   $this
     *
     *
     * 实现思路：
     *
     * 1, 根据业务参数，计算出 [表名]
     * 2, 指定表信息 $this->setTable($tabname, $prefix)
     *
     */
    abstract public function dynamicHash($params);



    /**
     * 根据主键查询
     *
     * @userid   int         用户[后期分布式]
     * @primary  int|array   主键
     * 
     * @return   array
     */
    function finds($userid, $primary)
    {
        //动态表
        $this->dynamicHash(['userid'=>$userid]);

        /**
         * 单条查询
         *
         */
        if (is_numeric($primary)) {
            $row = $this->where($this->getPrimary(), $primary)->find();

            return $row;
        }
        

        /**
         * 多条查询
         *
         */
        if (!is_array($primary) || count($primary) < 1) return false;

        $list = [];
        foreach ($primary as $ky => $sourceid) {
            $row    = $this->where($this->getPrimary(), $sourceid)->find();
            $list[] = ($row) ? $row : [];
        }

        return $list;
    }


    /**
     * 插入记录
     *
     * @userid   int         用户
     * @data     array       主键
     * 
     * @return   bool
     */
    function insert($userid, $data)
    {
        //动态表
        $this->dynamicHash(['userid'=>$userid]);

        return $this->allowField(true)->data($data)->isUpdate(false)->save();
    }


    /**
     * 根据主键删除
     *
     * @userid   int         用户[后期分布式]
     * @primary  int|array   主键
     * 
     * @return   int
     */
    function remove($userid, $primary)
    {
        //动态表
        $this->dynamicHash(['userid'=>$userid]);

        /**
         * 单条删除
         *
         */
        if (is_numeric($primary)) {
            $result = $this->where($this->getPrimary(), $primary)->delete();

            return $result;
        }
        

        /**
         * 多条删除
         *
         */
        if (!is_array($primary) || count($primary) < 1) return false;

        $list = [];
        foreach ($primary as $ky => $sourceid) {
            $result = $this->where($this->getPrimary(), $sourceid)->delete();
            if($result) { $list[] = $sourceid; }
        }

        return count($list);
    }



    /**
     * 根据主键更新
     *
     * @userid   int         用户[后期分布式]
     * @primary  int|array   主键
     * @data     array       更新数据
     * 
     * @return   int
     */
    function updateForId($userid, $primary, $data)
    {
        //动态表
        $this->dynamicHash(['userid'=>$userid]);
        
        /**
         * 单条更新
         *
         */
        if (is_numeric($primary)) {
            $result = $this->where($this->getPrimary(), $primary)->update($data);

            return $result;
        }
        

        /**
         * 多条更新
         *
         */
        if (!is_array($primary) || count($primary) < 1) return false;

        $list = [];
        foreach ($primary as $ky => $sourceid) {
            $result = $this->where($this->getPrimary(), $sourceid)->update($data);
            if($result) { $list[] = $sourceid; }
        }

        return count($list);
    }


    //搜索、分页、全文索引
    function search()
    {
        //动态表
        $this->dynamicHash(['userid'=>$userid]);
        
        return false;
    }

}