<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $users = $this->table('users');
        $users->addColumn('username', 'string', array('limit' => 255))
              ->addColumn('first_name', 'string', array('limit' => 50))
              ->addColumn('last_name', 'string', array('limit' => 50))
              ->addColumn('password', 'string', array('limit' => 255))
              ->addColumn('salt', 'string', array('limit' => 255))
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')
              ->addColumn('deleted_at', 'datetime', array('default' => null))
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('users');
    }
}
