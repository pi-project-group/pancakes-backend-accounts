<?php

use pancakes\kernel\base\utils\OtherUtils;
use pancakes\accounts\repository\ar\AccountsAR;

return [
    'user_server' => [
        'id' => 1,
        'public_key' => OtherUtils::generatePublicKey(),
        'username' => 'server',
        'email' => 'server@example.com',
        'password_hash' => Yii::$app->getSecurity()->generatePasswordHash(Yii::$app->getSecurity()->generateRandomString()),
        'auth_token' => Yii::$app->getSecurity()->generateRandomString(),
        'status' => AccountsAR::STATUS_ACTIVE,
        'role' => AccountsAR::ROLE_USER,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    'user_admin' => [
        'id' => 2,
        'public_key' => OtherUtils::generatePublicKey(),
        'username' => 'admin',
        'email' => 'admin@example.com',
        'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('admin'),
        'auth_token' => 'admin',
        'status' => AccountsAR::STATUS_ACTIVE,
        'role' => AccountsAR::ROLE_PRIVILEGED,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    'user_good_balance' => [
        'id' => 3,
        'public_key' => OtherUtils::generatePublicKey(),
        'username' => 'user_good_balance',
        'email' => 'user_good_balance@example.com',
        'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('user_good_balance'),
        'auth_token' => 'admin',
        'status' => AccountsAR::STATUS_ACTIVE,
        'role' => AccountsAR::ROLE_USER,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    'user_bad_balance' => [
        'id' => 4,
        'public_key' => OtherUtils::generatePublicKey(),
        'username' => 'user_bad_balance',
        'email' => 'user_bad_balance@example.com',
        'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('user_bad_balance'),
        'auth_token' => 'admin',
        'status' => AccountsAR::STATUS_ACTIVE,
        'role' => AccountsAR::ROLE_USER,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
];