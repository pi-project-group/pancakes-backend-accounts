<?php

namespace pancakes\accounts\repository\migrations;

use Yii;
use yii\db\Migration;

class m160606_044538_create_table_accounts extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%accounts}}', [
            'id' => $this->primaryKey()->unsigned(),
            'public_key' => $this->char(32)->unique()->notNull(),
            'username' => $this->string()->notNull(),
            'avatar_obj_id' => $this->integer()->unsigned(),
            'auth_token' => $this->char(64)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'new_email' => $this->string(),
            'status_of_email_confirm' => $this->boolean(),
            'email_confirm_token' => $this->string()->comment('Токен подтверждения смены email'),
            'email_confirm_code' => $this->string()->comment('Код подтверждения смены email'),
            'email_confirm_generated_keys_at' => $this->string()->comment('Время отправки ключей подтверждения на email'),
            'auth_secure_type' =>  $this->tinyInteger()->notNull()->unsigned()->defaultValue(0)->comment('Тип безопасной авторизации'),
            'auth_secure_code' => $this->string()->comment('Код безопасной авторизации'),
            'failed_auth_counter' => $this->integer()->unsigned()->notNull()->comment('Кол-во не удачных авторизаций'),
            'status' => $this->smallInteger()->notNull()->defaultValue('0'),
            'role' =>  $this->smallInteger()->notNull()->defaultValue(1)->comment('Роль пользователя'),
            'created_at' => $this->timestamp()->defaultValue(null),
            'updated_at' => $this->timestamp()->defaultValue(null),
            'last_activity' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('idx-accounts-public_key-unique', '{{%accounts}}', 'public_key', true);
        $this->createIndex('idx-accounts-username-unique', '{{%accounts}}', 'username', true);
        $this->createIndex('idx-accounts-auth_token-unique', '{{%accounts}}', 'auth_token', true);
        $this->createIndex('idx-accounts-email-unique', '{{%accounts}}', 'email', true);

        $dataFile = require Yii::getAlias('@vendor') . '/pancakes/accounts/tests/_data/accounts.php';
        $this->insert('{{%accounts}}' , $dataFile['user_server']);
        $this->insert('{{%accounts}}' , $dataFile['user_admin']);
    }

    public function down()
    {
        $this->dropTable('{{%accounts}}');
    }
}
