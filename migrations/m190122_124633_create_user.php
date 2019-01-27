<?php

use yii\db\Migration;

/**
 * Class m190122_124633_create_user
 */
class m190122_124633_create_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->createTable('user', [
        'id' => $this->primaryKey(),
        'username' => $this->string()->notNull(),
        'password_hash' => $this->string()->notNull(),
        'auth_key' => $this->string(),
        'creator_id' => $this->integer()->notNull(),
        'updater_id' => $this->integer(),
        'created_at' => $this->integer()->notNull(),
        'updated_at' => $this->integer(),
      ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190122_124633_create_user cannot be reverted.\n";

        return false;
    }
    */
}
