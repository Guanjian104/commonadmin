<?php
/**
 * Created by PhpStorm.
 * User: XiangHuaJie
 * Date: 2017/4/11 0011
 * Time: 10:03
 */

namespace app\admin\controller;

use think\Controller;
use think\Config;
use think\Session;
use think\Cookie;

class Login extends Controller
{
    /**
     * 登录首页
     * @access public
     * @return array
     */
    public function index()
    {
        if (Session::has('userInfo')){
            //Session存在自动登录
            $se = Session::get("userInfo");
            $this->autoLogin($se['username'],$se['password'])['status'] ?  $this->redirect('/Index/index') : "";
        }else{
            if(Cookie::has("userInfo")){
                //Cookie存在自动登录
                $se = unserialize(Cookie::get("userInfo"));
                $infos = $this->autoLogin($se['username'],$se['password']);
                if ($infos['status']){
                    Session::set('userInfo',$infos['info'],'think');
                    $this->redirect('/Index/index');
                }
            }
        }
        return view("Login/Index");
    }

    /**
     * 检测登录数据
     * @access public
     * @return array
     */
    public function check(){
        $username   = input('post.username/s');         //获取用户名
        $password   = input('post.password/s');         //获取输入密码
        $rememberme = input('post.rememberme');         //获取是否保存登录状态


        if($username == ''){
            return ['status'=>false,"msg"=>'请输入用户名'];
        }

        if($password == ''){
            return ['status'=>false,"msg"=>'请输入密码'];
        }

        $rememberme = $rememberme == true ? true : false ;      //判断是否保存登录状态

        $infos = $this->login($username ,$password);             //数据库验证登录账号密码   
        if($infos['status']){
            //成功登录保存session信息
            Session::set('userInfo',$infos['info'],'think');
            if($rememberme == true){
                //cookie保存登录状态
                Cookie::set("userInfo",serialize($infos['info']));
            }
            return ['status'=>true];
        }else{
            return ['status'=>false,"msg"=>"用户名和密码不匹配"];
        }
    }

    /**
     * 检测登录
     * @param $username
     * @param $password
     * @return mixed
     */
    public function login($username ,$password){
        $model = model('User');
        return $model->checkLogin($username ,$password);
    }

    /**
     * 检测自动登录
     * @param $username
     * @param $password
     * @return mixed
     */
    public function autoLogin($username ,$password){
        $model = model('User');
        return $model->autoCheckLogin($username ,$password);
    }

    /**
     * 注销
     * @access public
     * @return void
     */
    public function clearLogin(){
        //清除session  cookie  并跳转登录页面
        Session::delete("userInfo","think");
        Cookie::delete("userInfo");
        $this->redirect('/Login/index');
    }


    /**
     * 锁屏
     * @access public
     * @return void
     */
    public function clear(){
        //清除session  cookie
        Session::delete("userInfo","think");
        Cookie::delete("userInfo");
    }
}