<?php

use think\migration\Seeder;

class AdminUsers extends Seeder
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
                'openid'    => '',
                'username'  => 'admin',
                'password'  => 'bdf9f0864d4840bd62648ee644029b4e',
                'name'      => '管理员',
                'mobile'    => '13428705844',
                'visible'   => 1,
                'logintime' => 0
            )
        );
        $admin_user = $this->table('admin_user');
        $admin_user->insert($data)->save();
    }
}