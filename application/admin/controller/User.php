<?php
/**
 * Created by Eclipse.
 * User: Administrator
 * Date: 2017/4/17
 * Time: 09:24
 */

namespace app\admin\controller;

use app\admin\controller;
use think\Config;

class User extends Base
{
	//分页 每页15条记录
	private $pageSize = 15;
	
	/**
	 * 显示成员信息
	 * @access public
	 * @return \think\response\View
	 */
	public function index(){
		
		$page  = input("get.page/d") > 1 ? input("get.page/d") : 1;
		
		//搜索时 传递参数
		$k_group_id  = input('get.k_group_id/d');
		$k_username  = trim(urldecode(input('get.k_username/s')));
		$k_name      = trim(urldecode(input('get.k_name/s')));
		$k_mobile    = trim(urldecode(input('get.k_mobile/s')));
		
		$keyword_array = array();
		$option  = array();
		
		if($k_group_id !== "")
		{
			$keyword_array[] = "k_group_id=$k_group_id";
			$option['group_id'] = $k_group_id;
		}
		if(!empty($k_username))
		{
			$keyword_array[] = "k_username=$k_username";
			$option['username'] = $k_username;
		}
		if(!empty($k_name))
		{
			$keyword_array[] = "k_name=$k_name";
			$option['name'] = $k_name;
		}
		if(!empty($k_mobile))
		{
			$keyword_array[] = "k_mobile=$k_mobile";
			$option['mobile'] = $k_mobile;
		}
		
		$keyword = "";
		if (!empty($keyword_array))
		{
			$keyword = implode("&",$keyword_array);
		}
		
		$user      = model('User');
		$total     = $user->getCount($option);
		$pagetotal = ceil($total/$this->pageSize);
		$info      = $user->getList($option,$page,$this->pageSize);
		
		$group      = model('Group');
		$group_info = $group -> getInfo($option=0);
		
		return view("/User/Index",[
					"info"         => $info,
					"group_info"   => $group_info,
					"keyword"      => $keyword,
					"k_group_id"   => $k_group_id,
					"k_username"   => $k_username,
					"k_name"       => $k_name,
					"k_mobile"	   => $k_mobile,
					"page"         => $page,
					'pagetotal'    => $pagetotal
		]);
	}
	
	/**
	 * 显示编辑弹框
	 * @access public
	 * @return \think\response\View
	 */
	public function tan(){
		$user_id  = input('get.userid/i');
		
		$option =array();
		if ($user_id)
		{
			$option['user_id'] = $user_id;
		}else{
			$option = 0;
		} 
  		$user  = model('User');
  		$info  = $user->getInfoByid($option);
  		
  		$group      = model('Group');
  		$group_info = $group -> getInfo($option=0);
  		
  		return view("/User/Tan",["info"=>$info,"group_info"=>$group_info]);
	}
	
	/**
	 * 显示个人信息弹窗
	 * @access public
	 * @return \think\response\View
	 */
	public function myinfo(){
		$user_id  = input('get.user_id/i');
		
		$option['user_id'] = $user_id;
		
		$user  = model('User');
		$info  = $user->getInfoByid($option);
		
		return view("/User/Myinfo",["info"=>$info]);
	}

    /**
     * 显示修改密码弹窗
     * @access public
     * @return \think\response\View
     */
    public function pwd(){
        return view("/User/Pwd");
    }

    /**
     * 修改密码
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function savePwd()
    {
        $user_id  = input('post.user_id/d');
        $o_pwd    = input('post.o_pwd/s');
        $n_pwd    = input('post.n_pwd/s');

        if(empty($user_id))
        {
            return ['status'=>false,"msg"=>'用户ID不存在'];
        }
        if(empty($o_pwd))
        {
            return ['status'=>false,"msg"=>'请输入原密码'];
        }
        if(empty($n_pwd))
        {
            return ['status'=>false,"msg"=>'请输入新密码'];
        }

        $userModel = new \app\admin\model\User();
        $user = $userModel->getInfoByid(['user_id'=>$user_id]);
        if(empty($user))
        {
            return ['status'=>false,"msg"=>'用户不存在'];
        }

        if(md5($o_pwd.config::get("token")) != $user['password'])
        {
            return ['status'=>false,"msg"=>'原密码不正确'];
        }

        $userModel->edituser(['user_id'=>$user_id,'password'=>md5($n_pwd.config::get("token"))]);
        return ['status'=>true];
    }
	
	/**
	 * 保存成员信息
	 * @access public
	 * @return array
	 */
	public function saveUser(){
		$user_id    = input('post.user_id/i');
		$username   = input('post.username/s');
		$password   = input('post.password/s');
		$group_id   = input('post.group_id/i');
		$name       = input('post.name/s');
		$mobile     = input('post.mobile/s');
		
		
		if($username == ''){
			return ['status'=>false,"msg"=>'请输入用户名'];
		}
		if($group_id== ''){
			return ['status'=>false,"msg"=>'请输入请输入所在组'];
		}
		if($name == ''){
			return ['status'=>false,"msg"=>'请输入姓名'];
		}
		if($mobile == ''){
			return ['status'=>false,"msg"=>'请输入手机号码'];
		}
		
		$option=array();
		$option['group_id']   = $group_id;
		$option['name']       = $name;
		$option['mobile']     = $mobile;
		
		$user = model('User');
		if ($user_id) {                         
			//修改成员信息
			$option['user_id']    = $user_id;
			
			$usern = json_decode($user->getInfoByid(['user_id'=>$user_id]),true)['username'];
			
			if ( $username != $usern){  
				//判断用户名是否修改
				$usern = $user->getByName($username);
				if ( $usern ){
					return ['status'=>false,"msg"=>'用户名已存在'];
				}
				$option['username'] = $username;
			}
			
			if ( $password !== ""){   
				//不为空则修改
				$option['password'] = md5($password.config::get("token"));
			}
			$status = $user->edituser($option);
		}
		else { 
			//添加成员
			if($password == ''){
				return ['status'=>false,"msg"=>'请输入登录密码'];
			}
			
			$usern = $user->getByName($username);
			
			if ( $usern ){
				return ['status'=>false,"msg"=>'用户名已存在'];
			}
			
			$option['username'] = $username;
			$option['password'] = md5($password.config::get("token"));
			
			$status = $user->adduser($option);
		}
			
		
		if ($status === 0)
		{
			$status = true;
		}
		return ['status'=>$status];
	}
	
	/**
	 * 删除成员信息
	 * @access public
	 * @return array
	 */
	public function delUser(){
		$user_id = input('post.user_id/i');
		
		if($user_id == '') {
			return ['status' => false, "msg" => '参数不正确'];
		}
		$user   = model('User');
		$status = $user->delUser($user_id);
		
		return ['status'=>$status];
	}
}