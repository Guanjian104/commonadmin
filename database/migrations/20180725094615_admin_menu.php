<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdminMenu extends Migrator
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
        $table = $this->table('admin_menu',array('id'=>false,'primary_key'=>array('menu_id'),'engine'=>'InnoDB','comment'=>'后台主菜单'));
        $table->addColumn('menu_id','integer',array('limit'=>10,'signed'=>false,'null'=>false,'identity'=>true,'comment'=>'菜单ID'))
            ->addColumn('menu_name','string',array('limit'=>100,'null'=>false,'default'=>'','comment'=>'菜单名称'))
            ->addColumn('icon','string',array('limit'=>100,'null'=>false,'default'=>'fa-cubes','comment'=>'图标编号'))
            ->addColumn('sortid','integer',array('limit'=>4,'signed'=>false,'null'=>false,'default'=>0,'comment'=>'排序'))
            ->addColumn('visible','integer',array('limit'=>1,'signed'=>false,'null'=>false,'default'=>1,'comment'=>'是否有效（1：有效；0：删除）'))
            ->create();
    }
}
