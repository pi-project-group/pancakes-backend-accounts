<?php

use pancakes\accounts\repository\ar\AccountsAR;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $account AccountsAR */
?>
Дорогой <?= $account->username ?>,
Ваш логин на сайте <?= env('PROJECT_NAME')?> был изменен на новый - <?= $account->username ?>.
Если это произошло без Вашего ведома – срочно зайдите в свой профиль, используя Ваш E-Mail, смените логин и пароль.
Если у Вас возникнут проблемы, обратитесь в техническую поддержку
<?= Html::mailto(env('SUPPORT_EMAIL'), env('SUPPORT_EMAIL')) ?>
С уважением, Администрация <?= env('PROJECT_NAME')?>
