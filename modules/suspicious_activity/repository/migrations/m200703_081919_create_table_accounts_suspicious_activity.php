<?php

namespace pancakes\accounts\modules\suspicious_activity\repository\migrations;

use yii\db\Migration;

class m200703_081919_create_table_accounts_suspicious_activity extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%accounts_suspicious_activity}}', [
            'id' => $this->primaryKey()->unsigned(),
            'type_id' => $this->integer()->unsigned()->notNull()->comment('Тип активности'),
            'account_id' => $this->integer()->unsigned()->notNull(),
            'is_auto_ban' => $this->boolean()->comment('Автобан'),
            'count' => $this->integer()->notNull()->comment('Количество'),
            'status' => $this->integer()->notNull()->comment('Состояние активности'),
            'checked_account_id' => $this->integer()->unsigned()->unsigned()->comment('Проверено пользователем'),
            'comment_internal' => $this->string()->notNull()->comment('Внутренний комментрий'),
            'checked_at' => $this->timestamp()->defaultValue(null)->comment('Время проверки'),
            'created_at' => $this->timestamp()->defaultValue(null)->comment('Время создания'),
            'updated_at' => $this->timestamp()->defaultValue(null)->comment('Время обновления'),
        ], $tableOptions);

        $this->addForeignKey(
            'accounts_suspicious_activity-type_id-fk',
            '{{%accounts_suspicious_activity}}',
            'type_id',
            '{{%accounts_suspicious_activity_types}}',
            'id', 'RESTRICT',
            'RESTRICT'
        );
        $this->addForeignKey(
            'accounts_suspicious_activity-account_id-fk',
            '{{%accounts_suspicious_activity}}',
            'account_id',
            '{{%accounts}}',
            'id', 'RESTRICT',
            'RESTRICT'
        );

        $this->addForeignKey(
            'accounts_suspicious_activity-checked_account_id-fk',
            '{{%accounts_suspicious_activity}}',
            'checked_account_id',
            '{{%accounts}}',
            'id', 'RESTRICT',
            'RESTRICT'
        );
    }

    public function down()
    {
        $this->dropTable('{{%accounts_suspicious_activity}}');
    }
}
