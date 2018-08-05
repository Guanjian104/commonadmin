<?php

use think\migration\Seeder;

class AdminMenuItem extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = array(
            array(
                'menu_id'       => 1,
                'item_name'     => '菜单管理',
                'icon'          => '&#xe63c;',
                'purview_code'  => '',
                'url'           => '/menu/lists',
                'sortid'        => 1,
                'visible'       => 1
            ),
            array(
                'menu_id'       => 1,
                'item_name'     => '分组管理',
                'icon'          => '&#xe63c;',
                'purview_code'  => '',
                'url'           => '/group/index',
                'sortid'        => 2,
                'visible'       => 1
            ),
            array(
                'menu_id'       => 1,
                'item_name'     => '成员管理',
                'icon'          => '&#xe63c;',
                'purview_code'  => '',
                'url'           => '/user/index',
                'sortid'        => 3,
                'visible'       => 1
            )
        );
        $table = $this->table('admin_menu_item');
        $table->insert($data)->save();
    }
}