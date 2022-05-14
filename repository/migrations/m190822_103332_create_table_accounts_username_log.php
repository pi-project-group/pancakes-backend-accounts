<?php

namespace pancakes\accounts\repository\migrations;

use yii\db\Migration;

class m190822_103332_create_table_accounts_username_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%accounts_username_log}}', [
            'id' => $this->primaryKey()->unsigned(),
            'account_id' => $this->integer()->unsigned()->notNull(),
            'before_username' => $this->string()->comment('Предыдущий username'),
            'new_username' => $this->string()->notNull()->comment('Новый username'),
            'comment' => $this->string(),
            'author_id' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);

        $this->addForeignKey('accounts_username_log-account_id-fk', '{{%accounts_username_log}}', 'account_id', '{{%accounts}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('accounts_username_log-author_id-fk', '{{%accounts_username_log}}', 'author_id', '{{%accounts}}', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropTable('{{%accounts_status_log}}');
    }
}
