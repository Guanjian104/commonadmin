<?php
/**
 * Created by Eclipes.
 * User: lijunhua
 * Date: 2017/4/19
 * Time: 16:30
 */
namespace app\admin\controller;

use app\admin\controller\Base;
use app\admin\model\Menu;
use app\admin\model\GroupMenu;
use think\console\input\Option;
use think\Cache;

class Group extends Base
{
	//分页 每页15条记录
	private $pageSize = 15;
	
    /**
     * 显示首页外框
     * @access public
     * @return \think\response\View
     */
    public function index()
    {
    	$page  = input("get.page/d") > 1 ? input("get.page/d") : 1;
    	
    	$group      = model('Group');
    	$total      = $group->getCount();
    	$pagetotal  = ceil ($total/$this->pageSize);
    	$info       = $group->getList($page,$this->pageSize);
    	
    	return view("/Group/Index",[
    			"info"         => $info,
    			"page"         => $page,
    			'pagetotal'    => $pagetotal
    	]);
    }

    /**
     * 显示编辑弹框
     * @return \think\response\View
     */
    public function editGroup(){
    	$group_id  = input('get.group_id/d');
    	
    	$option['group_id'] = $group_id;
    	$option['visible'] = 1;
    	
    	$group  = model('Group');
    	$info   = $group->getInfo($option);
    	
    	$groupMenu = model('GroupMenu');
    	$itemIds   = $groupMenu->getItemIds($group_id);
    	
    	$menu      =  model('Menu');
    	$menuInfo  =  $menu -> getInfo($option=0);
    	
    	$menuitem  =  model('MenuItem');
    	$itemInfo  =  $menuitem -> getInfo($option=0);
    	
    	return view("/Group/EditGroup",[
    			"info"     => $info,
    			"itemIds"  => $itemIds,
    			"menuInfo" => $menuInfo,
    			"itemInfo" => $itemInfo	
    	]);
    }

    /**
     * 保存小组信息
     * @return array
     * @throws \think\exception\DbException
     */
    public function saveGroup(){
    	$group_id    = input('post.group_id/d');
    	$group_name  = input('post.group_name/s');
    	$itemids     = input('post.item_id/a');
    	
    	if (!$itemids)
    	{
    		$itemids = array();
    	}
    	
    	if($group_name== ''){
    		return ['status'=>false,"msg"=>'请输入小组名'];
    	}
    	
    	$option=array();
    	$option['group_id']   = $group_id;
    	$option['group_name'] = $group_name;
    	
    	//增加小组返回自增值
    	$group   = model('Group');
    	$groupid = $group->saveGroup($option);
		$status  =$groupid;


		$groupMenu = model("GroupMenu");
    	
	    $visible = 1;
    	$gm = new GroupMenu();
    	if(!$group_id)
    	{  
    		//添加小组
	    	foreach($itemids as $k=>$v){
	    		$gm = new GroupMenu();
	    		$gm->saveItem($groupid,$v,$visible);
	    	}
	    }else{
	    	//$itids 小组已有的权限
	    	$itids   = $gm -> getItemIds($group_id);
	    	
	    	
	    	$itid_d  = array_diff($itemids,$itids);
	    	$itid_s  = array_diff($itids,$itemids);
	    	if ($itid_d){
	    		foreach($itid_d as $k=>$v){
	    			$gm = new GroupMenu();  
	    			$gm->saveItem($group_id,$v,$visible);
	    		}
	    	}
	    	if ($itid_s){
	    		foreach($itid_s as $k=>$v){
	    			$gm = new GroupMenu();
	    			$gm->delItem($group_id,$v);
	    		}
	    	}
	   	}
    	
    	if ($status===0||$status!=false)
    	{
    		$status =true;
    	}else{
    		$status =false;
    	}

		//清楚菜单缓存
		Cache::rm("menu_$group_id");

		echo json_encode(["status"=>$status]);
    	//return ["status"=>$status];
    }
}
