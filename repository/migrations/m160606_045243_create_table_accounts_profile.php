<?php

namespace pancakes\accounts\repository\migrations;

use yii\db\Migration;

class m160606_045243_create_table_accounts_profile extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%accounts_profile}}', [
            'account_id' => $this->primaryKey()->unsigned(),
            'full_name' => $this->string(),
            'birth_dt' => $this->date(),
            'time_zone' => $this->string(),
            'utc' => $this->string(),
            'gender' => $this->tinyInteger()
        ], $tableOptions);

        $this->addForeignKey('accounts_profile-account_id-fk', '{{%accounts_profile}}', 'account_id', '{{%accounts}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%accounts_profile}}');
    }
}
