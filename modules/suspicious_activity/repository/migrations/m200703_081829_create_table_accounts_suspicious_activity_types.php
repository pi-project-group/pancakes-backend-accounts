<?php

namespace pancakes\accounts\modules\suspicious_activity\repository\migrations;

use yii\db\Migration;

class m200703_081829_create_table_accounts_suspicious_activity_types extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%accounts_suspicious_activity_types}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->comment('Название типа подозрительной активности пользователя'),
            'activity_threshold' => $this->boolean()->comment('Порог активности'),
            'is_auto_ban' => $this->boolean()->comment('Автобан'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%accounts_suspicious_activity_types}}');
    }
}
