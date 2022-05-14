<?php

namespace pancakes\accounts\tests\_fixtures;

use pancakes\accounts\repository\ar\AccountsAR;
use yii\test\ActiveFixture;
use yii\test\InitDbFixture;

class AccountsFixture extends ActiveFixture
{
    public $modelClass = AccountsAR::class;
    public $dataFile = '@vendor/pancakes/accounts/tests/_data/accounts.php';
    public $depends = [InitDbFixture::class];
}
