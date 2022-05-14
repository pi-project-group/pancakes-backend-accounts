<?php

use pancakes\accounts\repository\ar\AccountsAR;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $account AccountsAR */
/* @var $token string */

$url = sprintf(env('LINKS_PATH_MAIL_RESET_PASSWORD_BY_TOKEN'), $token);
?>

Запрос на восстановление пароля для учетной записи: <?= Html::encode($account->email) ?> (<?= Html::encode($account->username) ?>).
Для восстановления пароля на сайте <?= env('PROJECT_NAME')?>, нажмите на ссылку ниже или скопируйте её и вставьте в браузер:
<?= Html::encode($url) ?>.<br>
Если Вы получили это письмо по ошибке, просто проигнорируйте его.
