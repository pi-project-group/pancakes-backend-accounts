<?php

use pancakes\accounts\repository\ar\AccountsEmailsLogAR;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $accountsEmail AccountsEmailsLogAR */

$url = sprintf(env('LINKS_PATH_CONFIRM_EMAIL_BY_TOKEN'), $accountsEmail->confirm_token);
?>
<div>
    <p>
        Для подтверждения адреса электронной почты на сайте <?= env('PROJECT_NAME')?>, нажмите на ссылку ниже или скопируйте её и вставьте в браузер:<br>
        <?= Html::a(Html::encode($url), $url) ?>.<br>
        Если Вы получили это письмо по ошибке, просто проигнорируйте его.
    </p>
</div>
