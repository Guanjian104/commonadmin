<?php

use think\migration\Seeder;

class AdminMenu extends Seeder
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
                'menu_name' => '系统管理',
                'icon'      => 'fa-cubes',
                'sortid'    => 10,
                'visible'   => 1
            )
        );
        $table = $this->table('admin_menu');
        $table->insert($data)->save();
    }
}