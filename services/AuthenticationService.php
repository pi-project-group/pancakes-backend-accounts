<?php

namespace pancakes\accounts\services;

use pancakes\accounts\events\CreateAccountEvent;
use pancakes\accounts\exceptions\AuthFailException;
use pancakes\accounts\exceptions\SecurityCodeRequiredException;
use pancakes\accounts\repository\ar\AccountsAuthLogAR;
use pancakes\kernel\base\events\EventDispatcher;
use pancakes\kernel\base\utils\OtherUtils;
use Exception;
use pancakes\accounts\base\AccountIdentity;
use pancakes\accounts\repository\ar\AccountsProfileAR;
use pancakes\notifications\modules\emails\services\NotificationsEmailService;
use Yii;
use yii\web\Request;

/**
 * Регистрация и авторизация пользователей
 */
class AuthenticationService
{
    protected $dataGateway;

    public function __construct(AccountIdentity $dataGateway = null)
    {
        $this->dataGateway = !empty($dataGateway) ? $dataGateway : new AccountIdentity();
    }

    /**
     * Создание нового аккаунта
     * @param $email
     * @param $password
     * @param Request $request
     * @param bool $send_email_notice Отправить пользователю уведомление о регистрации
     * @return AccountIdentity
     * @throws Exception
     */
    public function createAccount($email, $password, Request $request, bool $send_email_notice){
        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            /** @var $account AccountIdentity */
            $account = new $this->dataGateway();
            $account->username = $account::generateUserNameByEmail($email);
            $account->email = $email;
            $account->status_of_email_confirm = false;
            $account->status = $account::STATUS_ACTIVE;
            $account->role = $account::ROLE_USER;
            $account->password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
            $account->auth_token = OtherUtils::generateToken();
            $account->generateEmailConfirmTokens();
            $account->save(false);

            $profile = new AccountsProfileAR();
            $profile->account_id = $account->id;
            $profile->save(false);

            $accountsStatusService = new AccountsStatusService();
            $accountsStatusService->loggedFirstAccountStatus($account);

            $accountsAuthLogService = new AccountsAuthLogService();
            $accountsAuthLogService->addAuthLog($account, $request);
            Yii::$container->get(EventDispatcher::class)->dispatch(new CreateAccountEvent($account));

            if($send_email_notice){
                $notificationsEmailService = new NotificationsEmailService();
                $notificationsEmailService->createEmailNotification(
                    'sign-up',
                    [
                        'email' => $email,
                        'password' => $password,
                        'email_confirm_url' => sprintf(env('LINKS_PATH_CONFIRM_EMAIL_BY_TOKEN'), $account->email_confirm_token),
                        'recovery_access_link' => env('LINKS_PATH_RECOVERY_ACCESS'),
                    ],
                    $account->email,
                    true
                );
            }
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
     * Проверка пароля аккаунта
     * @param AccountIdentity $account
     * @param $password
     * @return bool
     */
    public function checkAccountPassword(AccountIdentity $account, $password){
        return password_verify($password, $account->password_hash);
    }

    /**
     * Получение identity по логин паролю
     * @param $login
     * @param $password
     * @param $secure_code
     * @param Request $request
     * @return AccountIdentity
     * @throws AuthFailException
     * @throws SecurityCodeRequiredException
     * @throws Exception
     */
    public function authAccount($login, $password, $secure_code, Request $request){
        // Находим аккаунт
        /** @var $account AccountIdentity */
        $account = $this->dataGateway::queryFindAccountForLogin($login)->one();
        if (empty($account)) {
            throw new AuthFailException(Yii::t('package-accounts', 'Неверный логин или пароль'));
        }

        // Сверяем пароль
        if (!$this->checkAccountPassword($account, $password)) {
            $account->updateCounters(['failed_auth_counter' => 1]);
            $account->save(false);
            throw new AuthFailException(Yii::t('package-accounts', 'Неверный логин или пароль'));
        }

        // Проверяем состояние аккаунта
        if($account->status != $account::STATUS_ACTIVE) {
            throw new AuthFailException(Yii::t('package-accounts', 'Ваш аккаунт заблокирован'));
        }

        // Если есть auth_secure_code сначит в любом случае авторизируем только код защиты
        if(!empty($account->auth_secure_code)) {
            $codeParse = explode('|', $account->auth_secure_code);
            $codeHash = $codeParse[0];
            $codeExpire = $codeParse[1] + env('USER_SECURE_AUTH_TOKEN_EXPIRE');
            $codeFailCount = $codeParse[2];
            // Если срок жизни кода исктек или было много не успешных попыток
            if($codeFailCount >= 3 || $codeExpire < time()) {
                $this->generateAndSendAuthSecureCode($account);
                throw new SecurityCodeRequiredException(Yii::t('package-accounts', 'Сгенерирован новый код безопасности и отправлен вам на почтовый адрес'));
            }

            if(empty($secure_code)) {
                throw new SecurityCodeRequiredException(Yii::t('package-accounts', 'Необходимо ввести код безопасности'));
            }

            if(!password_verify($secure_code, $codeHash)) {
                $codeParse[2] += 1;
                $account->auth_secure_code = implode('|', $codeParse);
                $account->save(false);
                throw new SecurityCodeRequiredException(Yii::t('package-accounts', 'Некорректный код безопасности'));
            }
        }

        else {
            if ($account->failed_auth_counter > 6) {
                $this->generateAndSendAuthSecureCode($account);
                throw new SecurityCodeRequiredException(Yii::t('package-accounts', 'Активирована авторизация по подтверждению. Дальнейшие инструкции отправлены на вашу электронную почту.'));
            } elseif ($account->auth_secure_type == $account::AUTH_SECURE_TYPE_BY_IP){
                /** @var $last_auth AccountsAuthLogAR */
                $last_auth = AccountsAuthLogAR::find()->where(['account_id' => $account->id])->orderBy(['id' => SORT_DESC])->one();
                if($last_auth->inet_ip != inet_pton(Yii::$app->request->getUserIP())){
                    $this->generateAndSendAuthSecureCode($account);
                    throw new SecurityCodeRequiredException(Yii::t('package-accounts', 'Авторизация не удалась по причине измененного IP. Дальнейшие инструкции отправлены на вашу электронную почту.'));
                }
            }
            elseif ($account->auth_secure_type == $account::AUTH_SECURE_TYPE_ENABLED){
                $this->generateAndSendAuthSecureCode($account);
                throw new SecurityCodeRequiredException(Yii::t('package-accounts', 'Активирована авторизация по подтверждению. Дальнейшие инструкции отправлены на вашу электронную почту.'));
            }
        }

        $accountsAuthLogService = new AccountsAuthLogService();
        $accountsAuthLogService->addAuthLog($account, $request);

        // Обнуляем счетчик не успешных авторизаций и код авторизации если есть
        $account->failed_auth_counter = 0;
        $account->auth_secure_code = null;
        $account->save(false);

        return $account;
    }

    /**
     * Авторизация пользователя по переданному identity
     * @param AccountIdentity $account
     * @param Request $request
     * @param bool $remember
     * @return bool
     */
    public function login(AccountIdentity $account, Request $request, $remember = false){

        $accountsAuthLogService = new AccountsAuthLogService();
        $accountsAuthLogService->addAuthLog($account, $request);

        return Yii::$app->user->login($account, $remember ? 3600 * 24 * 30 : 0);
    }

    /**
     * Проверяет существование аккаунта по логину
     * @param $login
     * @return bool
     */
    public function isAccountEmailExists($login){
        return $this->dataGateway->queryFindAccountForLogin($login)->exists();
    }

    /**
     * @param AccountIdentity $account
     * @return bool
     * @throws Exception
     */
    private function generateAndSendAuthSecureCode(AccountIdentity &$account){
        $code = mt_rand(100000, 999999);
        $code_hash = password_hash($code, PASSWORD_BCRYPT, ['cost' => 5]);
        $account->auth_secure_code = $code_hash . '|' . time() . '|' . 0;
        $account->save(false);

        $notificationsEmailService = new NotificationsEmailService();
        $notificationsEmailService->createEmailNotification(
            'two-factor-auth',
            [
                'code' => $code,
                'username' => $account->username,
            ],
            $account->email,
            true
        );

        return true;
    }
}
