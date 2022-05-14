<?php

namespace pancakes\accounts\repository\migrations;

use yii\db\Migration;

class m190812_024706_create_table_accounts_email_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%accounts_email_log}}', [
            'id' => $this->primaryKey()->unsigned(),
            'account_id' => $this->integer()->unsigned()->notNull(),
            'prev_email' => $this->string()->comment('Предыдущий email'),
            'new_email' => $this->string()->notNull()->comment('Новый email'),
            'author_id' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultValue('0000-00-00 00:00:00'),
        ], $tableOptions);

        $this->addForeignKey('accounts_change_email_accounts_id_fk', '{{%accounts_email_log}}', 'account_id', '{{%accounts}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('accounts_change_email_author_id_fk', '{{%accounts_email_log}}', 'author_id', '{{%accounts}}', 'id', 'RESTRICT', 'RESTRICT');

    }

    public function down()
    {
        $this->dropTable('{{%accounts_email_log}}');
    }
}
