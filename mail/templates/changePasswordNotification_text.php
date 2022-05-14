<?php

use pancakes\accounts\repository\ar\AccountsAR;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $account AccountsAR */

$url = env('LINKS_PATH_RECOVERY_ACCESS');
?>
Дорогой <?= Html::encode($account->username) ?>,
Ваш пароль на сайте <?= env('PROJECT_NAME') ?> успешно изменён.
Если Вы этого не делали, Вам необходимо восстановить пароль, используя свой логин или электронную почту: <?= $url ?>
Если у Вас возникнут проблемы, обратитесь в техническую поддержку <?= env('SUPPORT_EMAIL')?>
-- С уважением, Администрация <?= env('PROJECT_NAME') ?>
