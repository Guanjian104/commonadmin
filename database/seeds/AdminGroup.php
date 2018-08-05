<?php

use think\migration\Seeder;

class AdminGroup extends Seeder
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
                'group_name' => '管理组',
                'visible'    => 1
            )
        );
        $table = $this->table('admin_group');
        $table->insert($data)->save();
    }
}