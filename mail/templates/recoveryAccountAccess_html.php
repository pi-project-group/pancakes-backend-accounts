<?php

use pancakes\accounts\repository\ar\AccountsAR;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $account AccountsAR */
/* @var $token string */

$url = sprintf(env('LINKS_PATH_MAIL_RESET_PASSWORD_BY_TOKEN'), $token);
?>
<div class="password-reset">
    Запрос на восстановление пароля для учетной записи: <?= Html::encode($account->email) ?> (<?= Html::encode($account->username) ?>).<br>
    Для восстановления пароля на сайте <?= env('PROJECT_NAME')?>, нажмите на ссылку ниже или скопируйте её и вставьте в браузер:<br>
    <?= Html::a(Html::encode($url), $url) ?>. Ссылка действительна в течении <?= env('USER_PASSWORD_RESET_TOKEN_EXPIRE') / 60 ?> минут.<br>
    Если Вы получили это письмо по ошибке, просто проигнорируйте его.
</div>
