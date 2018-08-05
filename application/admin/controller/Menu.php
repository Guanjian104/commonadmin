<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12 0012
 * Time: 17:00
 */

namespace app\admin\controller;

use app\admin\controller;
use app\admin\model;
use think\Cache;

class Menu extends Base
{

    //分页 每页15条记录
    private $pageSize = 15;

    /**
     * 显示主菜单列表
     * @access public
     * @return \think\response\View
     */
    public function lists(){
        $page    = input("get.page/d") > 1 ? input("get.page/d") : 1;     //当前页

        $option["visible"] = 1;

        //获取总页数
        $dg        = model('Menu');
        $total     = $dg->getCount($option);
        $pagetotal = ceil($total/$this->pageSize);
        $list      = $dg->getList($option,$page,$this->pageSize);


        return view("/Menu/Lists",[
            "list"=>$list,
            "page"=>$page,
            "pagetotal"=>$pagetotal,
        ]);
    }

    /**
     * 显示子菜单列表
     * @access public
     * @return \think\response\View
     */
    public function listson(){
        $page    = input("get.page/d") > 1 ? input("get.page/d") : 1;     //当前页
        $menu_id = input("get.menu_id/d") > 1 ? input("get.menu_id/d") : 1;  //主菜单id

        $option["visible"] = 1;
        if (!empty($menu_id)){
            $option["menu_id"] = $menu_id;      //查询条件添加主菜单id
        }
        //获取总页数
        $dg        = model('MenuItem');
        $total     = $dg->getCount($option);
        $pagetotal = ceil($total/$this->pageSize);
        $list      = $dg->getList($option,$page,$this->pageSize);


        return view("/Menu/Listson",[
            "list"=>$list,
            "menu_id"=>$menu_id,
            "page"=>$page,
            "pagetotal"=>$pagetotal,
        ]);
    }

    /**
     * 删除菜单
     * @access public
     * @return array
     */
    public function delmn(){
        $menu_id     = input('post.menu_id/d');     //获取主菜单id
        $item_id     = input('post.item_id/d');     //获取子菜单id

        if($menu_id != '') {
            //清除菜单缓存
            $this->clea_menu($menu_id);

            //删除主菜单
            $mc = model('Menu');
            $status = $mc->delmn($menu_id);

            return ['status'=>$status];
        }elseif ($item_id != ''){
            //删除子菜单
            $mc = model('MenuItem');
            $status = $mc->delmn($item_id);

            //清除菜单缓存
            $this->clea_item($item_id);

            return ['status'=>$status];
        }else{
            return ['status' => false, "msg" => '参数不正确'];
        }
    }

    /**
     * 显示编辑弹框
     * @access public
     * @return \think\response\View
     */
    public function tan(){
        $menu_id = input('get.menu_id/d');      //获取主菜单id

        $info = '';
        if ($menu_id != ''){
            //编辑主菜单
            $mn = model('Menu');
            $info = $mn->ggetInfo($menu_id);

            //判断图标是否fa开头
            $info['fa'] = strpos($info['icon'], "fa-") !== false ? 1 : 2;
            return view("/Menu/Tan",["info"=>$info]);
        }else{
            //添加主菜单
            return view("/Menu/Tan",["info"=>$info]);
        }
    }

    /**
     * 显示编辑弹框
     * @access public
     * @return \think\response\View
     */
    public function tanson(){
        $menu_id = input('get.menu_id/d');     //获取主菜单id
        $item_id = input('get.item_id/d');     //获取子菜单id

        $info = '';
        if ($item_id != '' && $menu_id != ''){
            //编辑子菜单
            $mt = model('MenuItem');
            $info = $mt->ggetInfo($item_id);

            //判断图标是否fa开头
            $info['fa'] = strpos($info['icon'], "fa-") !== false ? 1 : 2;
            return view("Menu/Tanson",["info"=>$info]);
        }elseif ($item_id == '' && $menu_id != ''){
            //添加子菜单
            return view("Menu/Tanson",["info"=>$info]);
        }else{
            return ['status' => false, "msg" => '参数不正确'];
        }
    }

    /**
     * 修改--增加 主菜单信息
     * @access public
     * @return array
     */
    public function editmn(){
        $menu_id     = input('post.menu_id/d');              //获取主菜单id
        $menu_name   = input('post.menu_name/s');            //获取主菜单名称
        $sortid      = input('post.sortid/d');               //获取主菜单排序
        $icon        = input('post.icon/s');                 //获取主菜单图标


        if($menu_name == ''){
            return ['status'=>false,"msg"=>'请输入菜单名称!'];
        }
        if($sortid === '' || $sortid > 255){
            return ['status'=>false,"msg"=>'排序号不正确!(范围0-255)'];
        }
        if($icon == ''){
            return ['status'=>false,"msg"=>'请选择图标'];
        }

        $mn = model('Menu');
        if (!empty($menu_id)){
            //修改主菜单
            $status = $mn->editmn(["menu_id"=>$menu_id,"menu_name"=>$menu_name,"icon"=>$icon,"sortid"=>$sortid]);

            //清除菜单缓存
            $this->clea_menu($menu_id);
        }else{
            //增加主菜单
            $status = $mn->editmn(["menu_name"=>$menu_name,"icon"=>$icon,"sortid"=>$sortid]);
        }
        return ['status'=>$status];
    }

    /**
     * 修改--增加 子菜单信息
     * @access public
     * @return array
     */
    public function editmt(){
        $menu_id     = input('get.menu_id/d');            //获取主菜单id
        $item_id     = input('post.item_id/d');           //获取子菜单id
        $item_name   = input('post.item_name/s');         //获取子菜单名称
        $url         = input('post.url/s');               //获取字菜单路径
        $sortid      = input('post.sortid/d');            //获取子菜单排序
        $icon        = input('post.icon/s');              //获取主菜单图标


        if($item_name == ''){
            return ['status'=>false,"msg"=>'请输入子菜单名称!'];
        }
        if($sortid == '' || $sortid > 255){
            return ['status'=>false,"msg"=>'排序号不正确!(范围0-255)'];
        }
        if($url == ''){
            return ['status'=>false,"msg"=>'请输入路径!'];
        }
        if($menu_id == ''){
            return ['status'=>false,"msg"=>'参数不正确'];
        }

        $mn = model('MenuItem');
        if (!empty($item_id)){
            //修改子菜单
            $status = $mn->editmt(["item_id"=>$item_id,"item_name"=>$item_name,"icon"=>$icon,"url"=>$url,"sortid"=>$sortid,"menu_id"=>$menu_id]);

            //清除菜单缓存
            $this->clea_item($item_id);
        }else{
            //增加子菜单
            $status = $mn->editmt(["item_name"=>$item_name,"icon"=>$icon,"url"=>$url,"sortid"=>$sortid,"menu_id"=>$menu_id]);
        }
        return ['status'=>$status];
    }

    /**
     * 选择菜单图标
     * @access public
     * @return \think\response\View
     */
    public function icon(){
        return view("/Menu/Icon");
    }

    //清除菜单缓存
    protected function clea_menu($menu_id){
        //读取主菜单下的子菜单信息
        $mi = model("MenuItem");
        $info = $mi->gItem(["menu_id"=>$menu_id,"visible"=>1]);

        foreach ($info as $k=>$v){
            $this->clea_item($v['item_id']);
        }
    }

    //清除菜单缓存
    protected function clea_item($item_id){
        $gm  = model("GroupMenu");
        $groupMenu = $gm->getInfo($item_id);    //读取子菜单所属于的组id
        if(!empty($groupMenu))
        {
            foreach($groupMenu as $group)
            {
                $gid = $group['group_id'];
                Cache::rm("menu_$gid");       //根据组id清理菜单缓存
            }
        }
    }
}
