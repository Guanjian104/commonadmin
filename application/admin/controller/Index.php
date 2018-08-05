<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use think\Cache;

class Index extends Base
{
    public function index(){

        return view("/index");
    }

    public function main(){
        return view("Index/Main");
    }

    public function clear_m(){
        Cache::rm("menu_1");
        echo 'ok';
    }
}
