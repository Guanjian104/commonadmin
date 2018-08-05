<?php

use think\migration\Seeder;

class AdminGroupMenu extends Seeder
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
                'group_id'  => 1,
                'item_id'   => 1,
                'visible'   => 1
            ),
            array(
                'group_id'  => 1,
                'item_id'   => 2,
                'visible'   => 1
            ),
            array(
                'group_id'  => 1,
                'item_id'   => 3,
                'visible'   => 1
            )
        );
        $table = $this->table('admin_group_menu');
        $table->insert($data)->save();
    }
}