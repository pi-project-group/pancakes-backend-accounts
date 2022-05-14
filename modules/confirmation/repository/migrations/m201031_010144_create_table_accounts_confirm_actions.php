<?php

namespace pancakes\accounts\modules\confirmation\repository\migrations;

use yii\db\Migration;

class m201031_010144_create_table_accounts_confirm_actions extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%accounts_confirm_actions}}', [
            'id' => $this->primaryKey(),
            'public_key' => $this->string(250)->comment('Публичный ключ'),
            'secret_key' => $this->string(250)->comment('Секретный ключ'),
            'data' => $this->string(1000)->comment('Данные действия'),
            'account_id' => $this->integer()->unsigned()->comment('Владелец действия'),
            'attempts' => $this->integer()->unsigned()->defaultValue(0)->comment('Попыток'),
            'created_at' => $this->timestamp()->defaultValue(null)->comment('Создан'),
            'confirmed_at' => $this->timestamp()->defaultValue(null)->comment('Активирован'),
        ], $tableOptions);
        $this->addCommentOnTable('{{%accounts_confirm_actions}}', 'Таблица с кодами подтверждения действий');

        $this->createIndex('idx-accounts_confirm_actions-unique', '{{%accounts_confirm_actions}}', ['public_key', 'account_id'], true);
        $this->addForeignKey('accounts_confirm_actions-account_id-fk', '{{%accounts_confirm_actions}}', 'account_id', '{{%accounts}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%accounts_confirm_actions}}');
    }
}
