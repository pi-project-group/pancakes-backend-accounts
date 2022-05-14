<?php

namespace pancakes\accounts\tests\fixtures;

use pancakes\accounts\repository\ar\AccountsProfileAR;
use yii\test\ActiveFixture;

class AccountsProfileFixture extends ActiveFixture
{
    public $modelClass = AccountsProfileAR::class;
    public $dataFile = '@vendor/pancakes/accounts/tests/fixtures/data/accounts_profile.php';
    public $depends = [AccountsFixture::class];
}
