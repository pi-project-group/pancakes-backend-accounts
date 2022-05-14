<?php

namespace pancakes\accounts\mail;

use pancakes\accounts\repository\ar\AccountsAccessRecoveryAR;
use pancakes\accounts\repository\ar\AccountsAR;
use pancakes\accounts\repository\ar\AccountsEmailsLogAR;
use pancakes\notifications\modules\emails\services\models\MailTemplateModel;
use Yii;

class KernelMailTemplates extends MailTemplateModel
{
    /**
     * Шаблон письма "Запрос на восстановление доступа к аккаунту"
     * @param AccountsAR $account
     * @param AccountsAccessRecoveryAR $accountsEmail
     * @return KernelMailTemplates
     */
    public static function getRecoveryAccountAccessTemplate(AccountsAR $account, AccountsAccessRecoveryAR $accountsEmail){
        $model = new self();
        $model->subject = Yii::t('package-accounts', 'Запрос на восстановление доступа к аккаунту');
        $model->textBody =Yii::$app->view->renderFile(self::getTemplate('recoveryAccountAccess_text'), [
            'account' => $account,
            'token' => $accountsEmail->token
        ]);
        $model->htmlBody = Yii::$app->view->renderFile(self::getTemplate('recoveryAccountAccess_html'), [
            'account' => $account,
            'token' => $accountsEmail->token
        ]);
        return $model;
    }

    /**
     * Шаблон письма "Ваш пароль был успешно изменен"
     * @param AccountsAR $account
     * @return KernelMailTemplates
     */
    public static function getChangePasswordNotificationTemplate(AccountsAR $account){
        $model = new self();
        $model->subject = Yii::t('package-accounts', 'Ваш пароль был успешно изменен');
        $model->textBody =Yii::$app->view->renderFile(self::getTemplate('changePasswordNotification_text'), [
            'account' => $account
        ]);
        $model->htmlBody = Yii::$app->view->renderFile(self::getTemplate('changePasswordNotification_html'), [
            'account' => $account
        ]);
        return $model;
    }

    /**
     * Шаблон письма "Ваш пароль был успешно изменен"
     * @param AccountsAR $account
     * @return KernelMailTemplates
     */
    public static function getChangeLoginNotificationTemplate(AccountsAR $account){
        $model = new self();
        $model->subject = Yii::t('package-accounts', 'Ваш логин был успешно изменен');
        $model->textBody =Yii::$app->view->renderFile(self::getTemplate('changeLogin_text'), [
            'account' => $account
        ]);
        $model->htmlBody = Yii::$app->view->renderFile(self::getTemplate('changeLogin_html'), [
            'account' => $account
        ]);
        return $model;
    }

    /**
     * Шаблон письма "Подтверждение адреса электронной почты"
     * @param AccountsEmailsLogAR $accountsEmail
     * @return KernelMailTemplates
     */
    public static function getEmailConfirmTemplate(AccountsEmailsLogAR $accountsEmail){
        $model = new self();
        $model->subject = Yii::t('package-accounts', 'Подтверждение адреса электронной почты');
        $model->textBody =Yii::$app->view->renderFile(self::getTemplate('emailConfirm_text'), [
            'accountsEmail' => $accountsEmail
        ]);
        $model->htmlBody = Yii::$app->view->renderFile(self::getTemplate('emailConfirm_html'), [
            'accountsEmail' => $accountsEmail
        ]);
        return $model;
    }

    /**
     * Шаблон письма "Подтверждение адреса электронной почты"
     * @param AccountsAR $account
     * @param AccountsEmailsLogAR $accountsEmail
     * @return KernelMailTemplates
     */
    public static function getChangeEmailRequestTemplate(AccountsAR $account, AccountsEmailsLogAR $accountsEmail){
        $model = new self();
        $model->subject = Yii::t('package-accounts', 'Подтверждение смены адреса электронной почты');
        $model->textBody =Yii::$app->view->renderFile(self::getTemplate('changeEmailRequest_text'), [
            'account' => $account,
            'changeEmailRequest' => $accountsEmail
        ]);
        $model->htmlBody = Yii::$app->view->renderFile(self::getTemplate('changeEmailRequest_html'), [
            'account' => $account,
            'changeEmailRequest' => $accountsEmail
        ]);
        return $model;
    }
    private static function getTemplate($template){
        return '@pancakes/accounts/mail/templates/' . $template . '.php';
    }
}
