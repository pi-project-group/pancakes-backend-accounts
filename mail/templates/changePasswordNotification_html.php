<?php

use pancakes\accounts\repository\ar\AccountsAR;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $account AccountsAR */

$url = env('LINKS_PATH_RECOVERY_ACCESS');
?>
<div>
    Дорогой <?= Html::encode($account->username) ?>,<br>
    Ваш пароль на сайте <?= env('PROJECT_NAME') ?> успешно изменён.<br>
    Если Вы этого не делали, Вам необходимо восстановить пароль, используя свой логин или электронную почту: <?= Html::a(Html::encode($url), $url) ?><br>
    Если у Вас возникнут проблемы, обратитесь в техническую поддержку <a href="mailto:<?= env('SUPPORT_EMAIL')?>"><?= env('SUPPORT_EMAIL')?></a><br>
    -- С уважением, Администрация <?= env('PROJECT_NAME') ?>
</div>
