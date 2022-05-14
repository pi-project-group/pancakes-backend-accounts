<?php

use pancakes\accounts\repository\ar\AccountsAR;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $account AccountsAR */
?>

<div>
    <p style="margin-top: 0;">Дорогой <?= $account->username ?>,</p>
    <p>Ваш логин на сайте <?= env('PROJECT_NAME')?> был изменен на новый - <strong><?= $account->username ?></strong>.</p>
    <p>Если это произошло без Вашего ведома – срочно зайдите в свой профиль, используя Ваш E-Mail, смените логин и пароль.</p>
    <p>Если у Вас возникнут проблемы, обратитесь в техническую поддержку
        <?= Html::mailto(env('SUPPORT_EMAIL'), env('SUPPORT_EMAIL')) ?>
    </p>
    <p>--<br>С уважением,<br>Администрация <?= env('PROJECT_NAME')?></p>
</div>
