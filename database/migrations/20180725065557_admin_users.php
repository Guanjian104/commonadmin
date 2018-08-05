<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdminUsers extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        // create the table
        $table = $this->table('admin_user',array('id'=>false,'primary_key'=>array('user_id'),'engine'=>'InnoDB','comment'=>'后台用户表'));
        $table->addColumn('user_id', 'integer',array('limit'=>10,'signed'=>false,'null'=>false,'identity'=>true,'comment'=>'用户ID'))
            ->addColumn('group_id', 'integer',array('limit'=>10,'signed'=>false,'null'=>false,'default'=>'0','comment'=>'小组ID'))
            ->addColumn('openid', 'string',array('limit'=>100,'null'=>false,'default'=>'','comment'=>'微信openid'))
            ->addColumn('username', 'string',array('limit'=>50,'null'=>false,'default'=>'','comment'=>'用户名'))
            ->addColumn('password', 'string',array('limit'=>50,'null'=>false,'default'=>'','comment'=>'密码'))
            ->addColumn('name', 'string',array('limit'=>50,'null'=>false,'default'=>'','comment'=>'姓名'))
            ->addColumn('mobile', 'string',array('limit'=>50,'null'=>false,'default'=>'','comment'=>'手机'))
            ->addColumn('visible', 'integer',array('limit'=>1,'signed'=>false,'null'=>false,'default'=>1,'comment'=>'是否有效（1：有效；0：删除）'))
            ->addColumn('logintime', 'integer',array('limit'=>10,'signed'=>false,'null'=>false,'default'=>0, 'comment'=>'登录时间'))
            ->addIndex(array('user_id','group_id'), array('unique' => true))
            ->addIndex(array('group_id'))
            ->create();
    }
}
