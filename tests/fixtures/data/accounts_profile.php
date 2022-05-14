<?php

$items = [
];
for ($i = 1; $i <= 85; $i++) {
    $items[] = [
        'account_id' => $i,
        'full_name' => '',
        'birth_dt' => '',
        'time_zone' => '',
        'utc' => '',
        'gender' => '',
    ];
}

return $items;
