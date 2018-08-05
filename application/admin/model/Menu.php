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

class Menu extends Model{

    //配置文件设置前缀dm $name对应表名
    protected $name = "admin_menu";

    /**
     * 查询主菜单详情
     * @param $menuids
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getMenuInfo($menuids){
        if (!is_array($menuids)){
            $menuids = [$menuids];
        }

        $options['visible']  = 1;
        $info = $this->where($options)->where("menu_id" , "in" , $menuids)->order("sortid asc")->select();

        return $info;
    }

    /**
     * 查询主菜单信息
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
     * @desc  软删除菜单
     * @access public
     * @param  integer $id 菜单id
     * @return boolean
     */
    public function delmn($id){
        //删除主菜单
        $this->save(['visible'=>0],['menu_id'=>$id]);
        //删除子菜单
        model("MenuItem")->save(['visible'=>0],['menu_id'=>$id]);
        return true;
    }

    /**
     * 查询主菜单详情
     * @param $id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public function ggetInfo($id)
    {
        return $this->get(['visible'=>1,'menu_id'=>$id]);
    }

    /**
     * 增加一条菜单
     * @param $arr
     * @return bool
     */
    public function editmn($arr){
        if (empty($arr['menu_id'])){
            //增加分类
            $info = $this->isUpdate(false)->save($arr);
            if ($info == true){
                return true;
            }
        }else{
            //修改分类
            $this->isUpdate(true)->save($arr,['menu_id'=>$arr['menu_id']]);
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