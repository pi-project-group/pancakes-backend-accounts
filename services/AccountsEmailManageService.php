<?php

namespace pancakes\accounts\services;

use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\utils\OtherUtils;
use pancakes\accounts\repository\ar\AccountsAR;
use pancakes\accounts\repository\ar\AccountsEmailsLogAR;
use pancakes\notifications\modules\emails\services\NotificationsEmailService;
use Yii;
use yii\db\Exception;
use yii\web\ForbiddenHttpException;

/**
 * @property AccountsEmailsLogAR $dataGateway
 */
class AccountsEmailManageService
{

    protected $dataGateway;

    public function __construct(AccountsEmailsLogAR $dataGateway = null)
    {
        $this->dataGateway = !empty($dataGateway) ? $dataGateway : new AccountsEmailsLogAR();
    }

    /**
     * Поиска аккаунта по токену подтверждения email
     * @param $token
     * @return array|AccountsAR|\yii\db\ActiveRecord
     * @throws ObjectNotFoundException
     */
    public function findAccountByEmailConfirmToken($token){
        /** @var $account AccountsAR */
        $account = AccountsAR::find()->where(['email_confirm_token' => $token])->one();
        if(!empty($account)) {
            $timestamp = (int) substr($token, strrpos($token, '_') + 1);
            $expire = env('USER_PASSWORD_RESET_TOKEN_EXPIRE');
            if($timestamp + $expire >= time()){
                return $account;
            }
        }
        throw new ObjectNotFoundException($token);
    }

    /**
     * Проверяет возможность повторной отправки токена на почту
     *
     * @param string|null $token password reset token
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function validateExceptionToken(string $token = null)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = env('USER_RESEND_TOKEN_TIMEOUT');
        $resendTokenLimitExpire = $timestamp + $expire >= time() ? $timestamp + $expire - time() : false;
        if($resendTokenLimitExpire) {
            $min = floor(($resendTokenLimitExpire / 60) % 60);
            $sec = $resendTokenLimitExpire % 60;
            throw new ForbiddenHttpException(Yii::t(
                'package-accounts',
                'Повторно отправить ссылку будет возможно через {0} мин. {1} сек.',
                [$min, $sec]
            ));
        }
        return false;
    }

    /**
     * Логирование email
     * @param $account_id
     * @param $prev_email
     * @param $new_email
     * @return AccountsEmailsLogAR
     */
    public function createLog($account_id, $prev_email, $new_email){
        $accountsEmail = new AccountsEmailsLogAR();
        $accountsEmail->account_id = $account_id;
        $accountsEmail->prev_email = $prev_email;
        $accountsEmail->new_email = $new_email;
        $accountsEmail->author_id = !empty(Yii::$app->user->id) ? Yii::$app->user->id : AccountsAR::SERVER_USER;
        $accountsEmail->save(false);
        return $accountsEmail;
    }

    /**
     * Создание запроса на смену пользовательского email адреса
     * @param AccountsAR $account
     * @param $newEmail
     * @throws \Exception
     */
    public function createRequestEditEmail(AccountsAR $account, $newEmail){
        if($account->email == $newEmail) {
            return;
        }
        $account->new_email = $newEmail;
        $account->status_of_email_confirm = $account::EMAIL_CHANGE_NOT_CONFIRM;
        $this->generatedEmailConfirmKeys($account);
    }

    public function generatedEmailConfirmKeys(AccountsAR $account){
        if($account->status != $account::EMAIL_CONFIRM_TRUE) {
            return;
        }
        $time = time();
        $account->email_confirm_token = OtherUtils::generateToken() . '_' . $time;
        $code = mt_rand(100000, 999999);
        $code_hash = password_hash($code, PASSWORD_BCRYPT, ['cost' => 5]);
        $account->email_confirm_code = $code_hash . '|' . $time . '|' . 0;
        $account->save(false);

        $notificationsEmailService = new NotificationsEmailService();

        if($account->status_of_email_confirm == $account::EMAIL_FIRST_NOT_CONFIRM){
            $notificationsEmailService->createEmailNotification(
                'confirm-new-email',
                [
                    'code' => $code,
                    'link' => sprintf(env('LINKS_PATH_CONFIRM_EMAIL_BY_TOKEN'), $account->email_confirm_token),
                ],
                $account->email,
                true
            );
        } elseif ($account->status_of_email_confirm == $account::EMAIL_NEW_NOT_CONFIRM) {
            $notificationsEmailService->createEmailNotification(
                'confirm-new-email',
                [
                    'code' => $code,
                    'link' => sprintf(env('LINKS_PATH_CONFIRM_EMAIL_BY_TOKEN'), $account->email_confirm_token),
                ],
                $account->new_email,
                true
            );
        }
        elseif ($account->status_of_email_confirm == $account::EMAIL_CHANGE_NOT_CONFIRM) {
            $notificationsEmailService->createEmailNotification(
                'confirm-change-email',
                [
                    'username' => $account->username,
                    'new_email' => $account->new_email,
                    'code' => $code,
                    'link' => sprintf(env('LINKS_PATH_CHANGE_EMAIL_REQUEST_CONFIRM_BY_TOKEN'), $account->email_confirm_token),
                ],
                $account->email,
                true
            );
        }
    }

    /**
     * Подтверждение пользовательского email адреса которое ожидает подтверждения
     * @param AccountsAR $account
     * @return AccountsAR
     * @throws \Exception
     */
    public function confirmEmailAccount(AccountsAR $account){
        if(!in_array($account->status_of_email_confirm, [$account::EMAIL_FIRST_NOT_CONFIRM, $account::EMAIL_NEW_NOT_CONFIRM])){
            throw new \Exception('Bad status_of_email_confirm');
        }
        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            if($account->status_of_email_confirm == $account::EMAIL_FIRST_NOT_CONFIRM){
                $this->createLog($account->id, null, $account->email);
            } else if($account->status_of_email_confirm == $account::EMAIL_NEW_NOT_CONFIRM) {
                $this->createLog($account->id, $account->email, $account->new_email);
                $account->email = $account->new_email;
            }
            $account->status_of_email_confirm = $account::EMAIL_CONFIRM_TRUE;
            $account->email_confirm_token = null;
            $account->email_confirm_code = null;
            $account->save(false);
            $transaction->commit();
            return $account;
        }
        catch(Exception $e)
        {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Подтверждение смены пользовательского email адреса которое ожидает подтверждения
     * @param AccountsAR $account
     * @return AccountsAR
     * @throws \Exception
     */
    public function confirmChangeEmailAccount(AccountsAR $account){
        if(!in_array($account->status_of_email_confirm, [$account::EMAIL_CHANGE_NOT_CONFIRM])){
            throw new \Exception('Bad status_of_email_confirm');
        }
        $account->status_of_email_confirm = $account::EMAIL_NEW_NOT_CONFIRM;
        $this->generatedEmailConfirmKeys($account);
    }

    /**
     * Отмена смены пользовательского email адреса
     * @param AccountsAR $account
     * @return AccountsAR
     * @throws \Exception
     */
    public function cancelChangeEmailAccount(AccountsAR $account){
        if($account->status_of_email_confirm == $account::EMAIL_FIRST_NOT_CONFIRM) {
            throw new \Exception();
        }
        $account->new_email = null;
        $account->status_of_email_confirm = $account::EMAIL_CONFIRM_TRUE;
        $account->email_confirm_code = null;
        $account->email_confirm_token = null;
        $account->save(false);
        return $account;
    }

    /**
     * Принудительная смена email пользователя
     * @param AccountsAR $account
     * @param string $newEmail
     * @return AccountsEmailsLogAR
     * @throws \Exception
     */
    public function forcedChangeAccountEmail(AccountsAR $account, string $newEmail){
        if($account->email == $newEmail) {
            return null;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            $log_model = $this->createLog($account->id, $account->email, $newEmail);
            $account->email = $newEmail;
            $account->save(false);

            $transaction->commit();

            return $log_model;
        }
        catch(Exception $e)
        {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }
}
