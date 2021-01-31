<?php

use yii\db\Migration;

/**
 * Class m210126_202731_news
 */
class m210126_202731_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'verification_token' =>  $this->string()->defaultValue(null),
            'access_token' =>  $this->string()->defaultValue(null)
        ], $tableOptions);

        $this->createTable('{{%news}}', [
            'id'            => $this->primaryKey(),
            'title'         => $this->string(128)->notNull()->comment('Титул'),
            'content'       => $this->text()->notNull()->comment('Контент'),
            'user_id'       => $this->integer()->notNull()->comment('Cоздатель'),
            'created_at'    => $this->integer()->notNull()->comment('Создан'),
            'updated_at'    => $this->integer()->comment('Обновлён'),
            'status'        => $this->smallInteger()->comment('Статус'),
        ], $tableOptions);

        $this->addForeignKey('fk-news-user', '{{%news}}', 'user_id', '{{%user}}', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-news-user', '{{%news}}');
        $this->dropTable('{{%user}}');

        $this->dropTable('{{%news}}');
    }
}
