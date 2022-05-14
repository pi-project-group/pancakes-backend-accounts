<?php

namespace pancakes\accounts\repository\migrations\fk;

use yii\db\Migration;

class m190916_034545_add_avatar_fk extends Migration
{
    public function up()
    {
        $this->addForeignKey('accounts-avatar_obj_id-fk', '{{%accounts}}', 'avatar_obj_id', '{{%filestorage_objects}}', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('accounts-avatar_obj_id-fk', '{{%accounts}}');
    }
}
