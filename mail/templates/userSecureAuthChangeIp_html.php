<?php

use pancakes\accounts\repository\ar\AccountsAR;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $account AccountsAR */

$code = explode('_', $account->auth_secure_code)[0];
?>

<div>
    Дорогой <?= !empty($account->username) ?>.<br>
    У вас настроена безопасная авторизация по IP адресу.<br>
    Для подтверждения авторизации с нового IP  адреса перейдите по ссылке, скопируйте и введите на сайте следующий код: <strong><?= $code ?></strong><br>
    Если у Вас возникнут проблемы, обратитесь в техническую поддержку <a href="mailto:<?= env('SUPPORT_EMAIL')?>"><?= env('SUPPORT_EMAIL')?></a><br>
    -- С уважением, Администрация <?= Yii::$app->name ?>
</div>
