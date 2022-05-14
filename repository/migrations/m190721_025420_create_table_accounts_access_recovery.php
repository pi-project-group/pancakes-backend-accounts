<?php

namespace pancakes\accounts\repository\migrations;

use yii\db\Migration;

class m190721_025420_create_table_accounts_access_recovery extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%accounts_access_recovery}}', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'token' => $this->string()->notNull(),
            'status' => $this->boolean()->notNull()->defaultValue(0)->comment('Сатус токена'),
            'created_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%accounts_access_recovery}}');
    }
}
