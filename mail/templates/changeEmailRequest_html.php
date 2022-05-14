<?php

use pancakes\accounts\repository\ar\AccountsAR;
use pancakes\accounts\repository\ar\AccountsEmailsLogAR;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $account AccountsAR */
/* @var $changeEmailRequest AccountsEmailsLogAR */

$url = sprintf(env('LINKS_PATH_CHANGE_EMAIL_REQUEST_CONFIRM_BY_TOKEN'), $changeEmailRequest->confirm_token);
?>
<div>
    <p style="margin-top: 0;">Дорогой <?= $account->username ?>,</p>
    <p>
        На сайте <?= env('PROJECT_NAME')?> был создан запрос на смену Email на новый - <strong><?= $changeEmailRequest->new_email ?></strong>.<br>
        Для подтверждения этой операции перейдите по сслыке <?= Html::a(Html::encode($url), $url) ?>
    </p>
    <p>Если это произошло без Вашего ведома – срочно зайдите в свой профиль, используя Ваш E-Mail, смените логин и пароль.</p>
    <p>Если у Вас возникнут проблемы, обратитесь в техническую поддержку
        <?= Html::mailto(env('SUPPORT_EMAIL'), env('SUPPORT_EMAIL')) ?>
    </p>
    <p>--<br>С уважением,<br>Администрация <?= env('PROJECT_NAME')?></p>
</div>
