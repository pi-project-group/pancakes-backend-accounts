<?php

namespace pancakes\accounts\tests\fixtures;

use pancakes\accounts\repository\ar\AccountsEmailsLogAR;
use yii\test\ActiveFixture;

class AccountsEmailsFixture extends ActiveFixture

{
    public $modelClass = AccountsEmailsLogAR::class;
    public $dataFile = '@vendor/pancakes/accounts/tests/fixtures/data/accountsEmails.php';
    public $depends = [AccountsFixture::class];
}
