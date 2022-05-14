<?php

use pancakes\accounts\repository\ar\AccountsAR;
use pancakes\accounts\repository\ar\AccountsEmailsLogAR;

/* @var $this yii\web\View */
/* @var $account AccountsAR */
/* @var $changeEmailRequest AccountsEmailsLogAR */

$url = sprintf(env('LINKS_PATH_CHANGE_EMAIL_REQUEST_CONFIRM_BY_TOKEN'), $changeEmailRequest->confirm_token);
?>
Дорогой <?= $account->username ?>,
На сайте <?= env('PROJECT_NAME')?> был создан запрос на смену Email на новый - <?= $changeEmailRequest->new_email ?>
Для подтверждения этой операции перейдите по сслыке <?= $url ?>
Если это произошло без Вашего ведома – срочно зайдите в свой профиль, используя Ваш E-Mail, смените логин и пароль.
Если у Вас возникнут проблемы, обратитесь в техническую поддержку
<?= env('SUPPORT_EMAIL') ?>
--С уважением, Администрация <?= env('PROJECT_NAME')?>
