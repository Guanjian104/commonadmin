<?php

namespace app\admin\controller;

use think\Controller;
use think\Session;
use think\Cookie;
use think\Cache;

class Base extends Controller
{
    protected $_session_user_id = "";
    public function _initialize()
    {
        parent::_initialize();

        //判断是否登录
        if(!Session::has("userInfo")){
            $this->redirect('/login/index');return false;
        }

        //读取所在组的主菜单ids
        $group = model("Group");
        $gid   = Session::get("userInfo")['group_id'];

        //判断缓存是否存在
        if(Cache::has("menu_$gid")){
            $menu  = Cache::get("menu_$gid");
        }else{
            $menu  = $group->getMenu($gid);
            Cache::set("menu_$gid",$menu);
        }


        $this->_session_user_id = Session::get("userInfo")['user_id'];

        $this->assign("data",json_encode($menu));
    }
}
