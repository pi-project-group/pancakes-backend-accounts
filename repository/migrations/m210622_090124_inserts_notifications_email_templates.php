<?php

namespace pancakes\accounts\repository\migrations;

use pancakes\kernel\base\utils\CSVHelper;
use Yii;
use yii\db\Migration;

class m210622_090124_inserts_notifications_email_templates extends Migration
{
    public function up()
    {
        $dataFilePath = Yii::getAlias('@pancakes') . "/accounts/repository/migrations/init_data/notifications_email_templates.csv";
        CSVHelper::toArray($dataFilePath, true, function ($row) {
            $this->insert('{{%notifications_email_templates}}' , $row);
        });
    }

    public function down()
    {
    }
}
