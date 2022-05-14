<?php

use pancakes\kernel\base\utils\OtherUtils;
use pancakes\accounts\repository\ar\AccountsAR;

$items = [
    [
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
    [
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
    [
        'id' => 3,
        'public_key' => OtherUtils::generatePublicKey(),
        'username' => 'moderator',
        'email' => 'moderator@example.com',
        'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('moderator'),
        'auth_token' => 'moderator',
        'status' => AccountsAR::STATUS_ACTIVE,
        'role' => AccountsAR::ROLE_USER,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'id' => 4,
        'public_key' => OtherUtils::generatePublicKey(),
        'username' => 'user',
        'email' => 'user@example.com',
        'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('user'),
        'auth_token' => 'user',
        'status' => AccountsAR::STATUS_ACTIVE,
        'role' => AccountsAR::ROLE_USER,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
];

for ($i = 100; $i <= 180; $i++) {
    $username = 'user' . $i;
    $items[] = [
        'public_key' => OtherUtils::generatePublicKey(),
        'username' => $username,
        'email' => $username . '@example.com',
        'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('user'),
        'auth_token' => $username,
        'status' => AccountsAR::STATUS_ACTIVE,
        'role' => AccountsAR::ROLE_USER,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
}

return $items;
