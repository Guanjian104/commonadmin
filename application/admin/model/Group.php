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

class Group extends Model{

    //配置文件设置前缀dm $name对应表名
    protected $name = "admin_group";


    /**
     * 查询并拼接菜单
     * @param $group_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getMenu($group_id){
        //读取子菜单ids
        $gm = new GroupMenu();
        $itemids =$gm->getItemIds($group_id);

        //读取子菜单详情
        $mt = new MenuItem();
        $iteminfo = $mt->getItemInfo($itemids);
        
        //拼接主菜单id  并拼接子菜单详情
        $menuids = array();
        foreach ($iteminfo as $k=>$v){
            $menuids[] = $v['menu_id'];

            $tmp_t  = array();
            $m_key  = "m_{$v['menu_id']}";              //定义可变变量
            empty($$m_key) ? $$m_key = array() : '';

            $tmp_t['title'] = $v['item_name'];
            $tmp_t['icon']  = $v['icon'];
            $tmp_t['href']  = $v['url'];

            array_push($$m_key,$tmp_t);                 //给可变变量添加元素
        }

        //读取主菜单详情
        $m = new Menu();
        $menuinfo = $m->getMenuInfo($menuids);
        
        //拼接所有菜单
        $menu = array();
        foreach ($menuinfo as $k=>$v){
            $tmp = array();
            $tmp['title']    = $v['menu_name'];
            $tmp['icon']     = $v['icon'];
            $tmp['spread']   = false;

            $m_key           = "m_{$v['menu_id']}";
            $tmp['children'] = $$m_key;                //可变变量中含有子菜单信息 赋值予主菜单children下
            $menu[] = $tmp;
        }

        return $menu;
    }

    /**
     * 查询小组信息
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
     * 获取小组列表
     * @param $page
     * @param $pageSize
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList( $page , $pageSize)
    {
    	return $this->where('visible=1')-> page("$page,$pageSize")->order("group_id asc")->select();
    }

    /**
     * 查询小组总数
     * @return int|string
     */
    public function getCount()
    {
    	return $this->where('visible=1')->count();
    }

    /**
     * 保存小组信息
     * @param $option
     * @return false|int|mixed
     */
    public function saveGroup($option){
    	if ($option['group_id']) 
    	{
    		return $this->save($option,['visible'=>1,'group_id'=>$option['group_id']]);
    	}else{
    		$this->save($option);
    		return $this->group_id;
    	}
    }
}