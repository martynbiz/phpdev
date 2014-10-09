<?php

use Phinx\Migration\AbstractMigration;

class CreatePostsTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $posts = $this->table('posts');
        $posts->addColumn('title', 'string', array('limit' => 255))
              ->addColumn('content', 'text')
              ->addColumn('user_id', 'integer')
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
        $this->dropTable('posts');
    }
}
