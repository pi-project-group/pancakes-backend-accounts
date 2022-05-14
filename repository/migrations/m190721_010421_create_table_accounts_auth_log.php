<?php

namespace pancakes\accounts\repository\migrations;

use yii\db\Migration;

class m190721_010421_create_table_accounts_auth_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%accounts_auth_log}}', [
            'id' => $this->primaryKey()->unsigned(),
            'account_id' => $this->integer()->unsigned(),
            'user_agent' => $this->string(),
            'device' => $this->string(),
            'os' => $this->string(),
            'browser' => $this->string(),
            'inet_ip' => $this->binary(16),
            'referer_url' => $this->string(),
            'created_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);

        $this->addForeignKey('accounts_auth_log-account_id-fk', '{{%accounts_auth_log}}', 'account_id', '{{%accounts}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%accounts_auth_log}}');
    }
}
