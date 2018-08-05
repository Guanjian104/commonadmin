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

class User extends Model{

    //配置文件设置前缀dm $name对应表名
    protected $name = "admin_user";

    /**
     * 检测登录
     * @param $username
     * @param $password
     * @return array
     * @throws \think\exception\DbException
     */
    public function checkLogin($username,$password){
		//读取用户信息
        $info = $this->get(['username'=>$username,"visible"=>1]);

		//拼接加密后的密码
        if ($info['password'] == md5($password.config::get("token"))){
            $this->upltime($info['user_id']);
            return ['status'=>true,'info'=>$info];
        }else{
            return ['status'=>false];
        }
    }

    /**
     * 检测自动登录
     * @param $username
     * @param $password
     * @return array
     * @throws \think\exception\DbException
     */
    public function autoCheckLogin($username,$password){
        $info = $this->get(['username'=>$username,"visible"=>1]);
        if ($info['password'] == $password){
            $this->upltime($info['user_id']);
            return ['status'=>true,'info'=>$info];
        }else{
            return ['status'=>false];
        }
    }

    /**
     * 更新登录时间
     * @param $id
     */
    public function upltime($id){
        $id = (int)$id;
        $this->where('user_id', $id)->update(['logintime' => time()]);
    }

    /**
     * 根据user_id查询成员信息
     * @param $option
     * @return array|false|null|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getInfoByid($option){
    	if($option){	
    		$that = $this;
	    	$option['visible'] = 1;
	    	$that = $this->_handleOption($option, $that);
	    	return $that-> find();
	    }else{
	    	return null;
	    }
    }

    /**
     * 根据user_name查询成员信息
     * @param $username
     * @return null|static
     * @throws \think\exception\DbException
     */
    public function getByName($username){
    	$visible = 1;
    	return $this-> get(['username' => $username,'visible' => $visible]);
    }

    /**
     * 获取成员列表
     * @param $option
     * @param $page
     * @param $pageSize
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($option , $page , $pageSize)
    {
    	$that = $this;
    	$option['visible'] = 1;
    	$that = $this->_handleOption($option, $that);
    	return $that->page("$page,$pageSize")->order("user_id desc")->select();
    }

    /**
     * 获取所有yoghurt
     * @param $option
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAll($option)
    {
        return $this->where($option)->select();
    }
    
    /**
     * @desc   查询成员总数
     * @access public
     * @param  integer $option 搜索条件
     * @return array
     */
    public function getCount($option){
    	$that = $this;
    	$option['visible'] = 1;
    	$that = $this->_handleOption($option, $that);
    	return $that->count();
    }

    /**
     * 添加成员
     * @param $arr
     * @return false|int
     */
    public function adduser($arr){
    	return $this->save($arr);
    }

    /**
     * 修改一条成员信息
     * @param $arr
     * @return false|int
     */
    public function edituser($arr){
    	return $this->save($arr,['visible'=>1,'user_id'=>$arr['user_id']]);
    }

    /**
     * 软删除成员
     * @param $id
     * @return bool
     */
    public function delUser($id){
    	$this->save(['visible'=>"0"],['user_id'=>$id]);
    	return true;
    }

    /**
     * 处理条件数组
     * @param $options
     * @param $that
     * @return mixed
     */
    private function _handleOption($options,$that){
    	if(empty($options)){
    		return $that;
    	}else{
    		$that->alias('a');
    	}
    	if (!empty($options['group_id'])){
    		$that->where('b.group_id',$options['group_id']);
    	}
    	if (!empty($options['user_id'])){
    		$that->where('a.user_id',$options['user_id']);
    	}
    	if (!empty($options['username'])){
    		$that->where('a.username','like',"%{$options['username']}%");
    	}
    	if (!empty($options['name'])){
    		$that->where('a.name','like',"%{$options['name']}%");
    	}
    	if (!empty($options['mobile'])){
    		$that->where('a.mobile','like',"%{$options['mobile']}%");
    	}
    	if (!empty($options['visible']))
    	{
    		$that->where('a.visible',$options['visible']);
    	}
    	
    	$that->join("cm_admin_group b", "a.group_id = b.group_id","LEFT");
    	return $that;
    }
}