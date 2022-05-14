<?php

use pancakes\accounts\repository\ar\AccountsAR;

$items = [];

$contracts = AccountsAR::find()->all();
foreach ($contracts as $contract) {
    $items[] = [
        'account_id' => $contract->id,
        'status' => $contract->status,
        'author_id' => AccountsAR::SERVER_USER,
        'created_at' => $contract->created_at,
    ];
}

return $items;
