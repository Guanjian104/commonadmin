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
use think\console\input\Option;

class GroupMenu extends Model{

    //配置文件设置前缀dm $name对应表名
    protected $name = "admin_group_menu";


    /**
     * @desc   查询子菜单id
     * @access public
     * @param  string $group_id 组id
     * @return array
     */
    public function getItemIds($group_id){
        $options['group_id'] = $group_id;   //组id
        $options['visible']  = 1;

        $that = $this;
        $that = $this->_handleOption($options, $that);
        $info = $that->field("item_id")->select();

        //拼接子菜单id
        $itemids = array();
        foreach ($info as $k=>$v){
            $itemids[] = $v['item_id'];
        }
        return $itemids;
    }

    /**
     * 查询组id
     * @param $itemid
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getInfo($itemid){
        $options['item_id'] = $itemid;
        $options['visible']  = 1;
        return $this->where($options)->select();
    }


    /**
     * 添加小组权限
     * @param $group_id
     * @param $item_id
     * @param $visible
     * @return false|int
     * @throws \think\exception\DbException
     */
    public function saveItem($group_id,$item_id,$visible){
    	$exItm = $this -> get(["group_id"=>$group_id,"item_id"=>$item_id]);
    	if ($exItm)
    	{
    		return $this ->save(["visible"=>$visible],["group_id"=>$group_id,"item_id"=>$item_id]);
    	}else {
    		return $this ->save(["group_id"=>$group_id,"item_id"=>$item_id,"visible"=>$visible]);
    	}
    }
    
    /**
     * @desc  软删除小组权限
     * @access public
     * @param  integer $group_id 组id $item_id 子菜单id
     * @return boolean
     */
    public function delItem($group_id,$item_id){
    	$this->save(['visible'=>"0"],['group_id'=>$group_id,'item_id'=>$item_id]);
    	return true;
    }

    private function _handleOption($options,$that){
        if(empty($options)){
            return $that;
        }else{
            $that->alias('a');
        }
        if (!empty($options['group_id'])){
            $that->where('a.group_id',$options['group_id']);
        }
        if (!empty($options['visible'])){
            $that->where('a.visible',$options['visible']);
        }
        return $that;
    }
}