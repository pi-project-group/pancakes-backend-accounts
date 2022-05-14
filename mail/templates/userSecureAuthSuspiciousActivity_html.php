<?php

use pancakes\accounts\repository\ar\AccountsAR;

/* @var $this yii\web\View */
/* @var $account AccountsAR */

$code = explode('_', $account->auth_secure_code)[0];
?>

<div>
    Дорогой <?= !empty($account->username) ?>.<br>
    Мы зафиксировали подозрительную активность при авторизации под вашей учетной записью.<br>
    Если это были не вы то рекомендуем изменить пароль доступа к ресурсу.
    Для того чтобы авторизироваться, скопируйте и введите на сайте следующий код: <strong><?= $code ?></strong><br>
    Если у Вас возникнут проблемы, обратитесь в техническую поддержку <a href="mailto:<?= env('SUPPORT_EMAIL')?>"><?= env('SUPPORT_EMAIL')?></a><br>
    -- С уважением, Администрация <?= Yii::$app->name ?>
</div>
