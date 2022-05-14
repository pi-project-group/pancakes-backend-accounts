<?php

use pancakes\accounts\repository\ar\AccountsAR;

$items = [];

$contracts = AccountsAR::find()->all();
/** @var $contract AccountsAR */
foreach ($contracts as $contract) {
    $items[] = [
        'account_id' => $contract->id,
        'new_email' => $contract->email,
        'author_id' => AccountsAR::SERVER_USER,
        'created_at' => $contract->created_at,
    ];
}

return $items;
