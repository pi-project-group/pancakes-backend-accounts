<?php

namespace pancakes\accounts\tests\fixtures;

use pancakes\accounts\repository\ar\AccountsStatusLogAR;
use yii\test\ActiveFixture;

class AccountsStatusLogFixture extends ActiveFixture

{
    public $modelClass = AccountsStatusLogAR::class;
    public $dataFile = '@vendor/pancakes/accounts/tests/fixtures/data/accountsStatusLog.php';
    public $depends = [AccountsFixture::class];
}
