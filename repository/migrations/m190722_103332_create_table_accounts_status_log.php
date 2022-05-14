<?php

namespace pancakes\accounts\repository\migrations;

use yii\db\Migration;

class m190722_103332_create_table_accounts_status_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%accounts_status_log}}', [
            'id' => $this->primaryKey()->unsigned(),
            'account_id' => $this->integer()->unsigned()->notNull(),
            'status' => $this->integer()->notNull(),
            'comment' => $this->string()->notNull(),
            'author_id' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);

        $this->addForeignKey('accounts_status_log-account_id-fk', '{{%accounts_status_log}}', 'account_id', '{{%accounts}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('accounts_status_log-author_id-fk', '{{%accounts_status_log}}', 'author_id', '{{%accounts}}', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropTable('{{%accounts_status_log}}');
    }
}
