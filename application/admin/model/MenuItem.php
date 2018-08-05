<?php
/**
 * Created by PhpStorm.
 * User: XiangHuaJie
 * Date: 2017/4/12 0012
 * Time: 09:15
 */

namespace app\admin\model;

use think\Db;
use \think\Model;
use think\Config;

class MenuItem extends Model{

    //配置文件设置前缀dm $name对应表名
    protected $name = "admin_menu_item";

    /**
     * 查询子菜单详情
     * @param $itemids
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getItemInfo($itemids){
        if (!is_array($itemids)){
            $itemids = [$itemids];
        }

        $options['visible']  = 1;
        $info = $this->where($options)->where("item_id" , "in" , $itemids)->order("sortid asc")->select();

        return $info;
    }

    /**
     * 查询子菜单信息
     * @param $option
     * @return false|null|\PDOStatement|string|\think\Collection|static
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getInfo($option){
    	if ($option)
    	{
    		return $this->get($option);
    	}else {
    		return $this->where('visible=1')->select();
    	}
    }

    /**
     * 查询子菜单信息
     * @param $option
     * @return mixed
     */
    public function gItem($option){
        $that = $this;
        $that = $this->_handleOption($option, $that);
        return $that->select();
    }

    /**
     * 查询主菜单总数
     * @param $option
     * @return mixed
     */
    public function getCount($option){
        $that = $this;
        $that = $this->_handleOption($option, $that);
        return $that->count();
    }

    /**
     * 查询主菜单详情
     * @param $option
     * @param int $page
     * @param int $pageSize
     * @return mixed
     */
    public function getList($option, $page=1,$pageSize=15)
    {
        $that = $this;
        $that = $this->_handleOption($option, $that);
        return $that->page("$page,$pageSize")->order("sortid asc")->select();
    }

    /**
     * 软删除子菜单
     * @param $id
     * @return bool
     */
    public function delmn($id){
        $this->save(['visible'=>0],['item_id'=>$id]);
        return true;
    }

    /**
     * 查询子菜单详情
     * @param $id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public function ggetInfo($id)
    {
        return $this->get(['visible'=>1,'item_id'=>$id]);
    }

    /**
     * @desc  增加一条子菜单
     * @access public
     * @param  array $arr 数据
     * @return boolean
     */
    public function editmt($arr){
        if (empty($arr['item_id'])){
            //增加分类
            $info = $this->isUpdate(false)->save($arr);
            if ($info == true){
                return true;
            }
        }else{
            //修改分类
            $this->isUpdate(true)->save($arr,['item_id'=>$arr['item_id']]);
            return true;
        }

        return false;
    }

    private function _handleOption($options,$that){
        if(empty($options)){
            return $that;
        }else{
            $that->alias('a');
        }
        if (!empty($options['visible'])){
            $that->where('a.visible',$options['visible']);
        }
        if (!empty($options['menu_id'])){
            $that->where('a.menu_id',$options['menu_id']);
        }

        return $that;
    }

}