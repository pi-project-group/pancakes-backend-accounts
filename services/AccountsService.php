<?php

namespace pancakes\accounts\services;

use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\accounts\mail\KernelMailTemplates;
use pancakes\accounts\repository\ar\AccountsAccessRecoveryAR;
use pancakes\accounts\repository\ar\AccountsAR;
use pancakes\accounts\repository\search\AccountsSearchModel;
use pancakes\notifications\modules\emails\services\NotificationsEmailService;
use yii\data\ActiveDataProvider;

/**
 * @property AccountsAR $dataGateway
 */
class AccountsService
{
    protected $dataGateway;

    public function __construct(AccountsAR $dataGateway = null)
    {
        $this->dataGateway = !empty($dataGateway) ? $dataGateway : new AccountsAR();
    }

    /**
     * @param AccountsSearchModel $searchModel
     * @return ActiveDataProvider
     */
    public function getAccountsDataProvider(AccountsSearchModel $searchModel) {
        return $searchModel->getActiveDataProvider();
    }

    /**
     * Получение аккаунта по Id
     * @param $account_id
     * @return AccountsAR
     * @throws ObjectNotFoundException
     */
    public function getAccountById($account_id) {
        /** @var $account AccountsAR */
        $account = $this->dataGateway::queryFindUser($account_id)->one();
        if(empty($account)) {
            throw new ObjectNotFoundException($account_id);
        }
        return $account;
    }

    /**
     * Получение аккаунта по Id
     * @param $publicKey
     * @return AccountsAR
     * @throws ObjectNotFoundException
     */
    public function getAccountByPublicKey($publicKey) {
        /** @var $account AccountsAR */
        $account = $this->dataGateway::queryFindUser(null, null, $publicKey)->one();
        if(empty($account)) {
            throw new ObjectNotFoundException($publicKey);
        }
        return $account;
    }

    /**
     * Получение аккаунта по email
     * @param $email
     * @return AccountsAR
     * @throws ObjectNotFoundException
     */
    public function getAccountByEmail($email) {
        /** @var $account AccountsAR */
        $account = $this->dataGateway::queryFindUser(null, $email)->one();
        if(empty($account)) {
            throw new ObjectNotFoundException($email);
        }
        return $account;
    }

    /**
     * Получение аккаунта по Id
     * @param $search_param
     * @return AccountsAR
     */
    static function getAccountBySearchParam($search_param) {
        /** @var $account AccountsAR */
        $account = AccountsAR::find()->where(['OR',
            ['id' => $search_param],
            ['public_key' => $search_param],
            ['username' => $search_param],
            ['email' => $search_param]
        ])->one();
        return $account;
    }

    /**
     * Изменение пароля пользователя
     * @param AccountsAR $account
     * @param $newPassword
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function changePassword(AccountsAR $account, $newPassword) {
        $account->password_hash = $this::generatePasswordHash($newPassword);
        $result = $account->save();

        AccountsAccessRecoveryAR::setAllOutdatedStatuses($account->id);

        $emailService = new NotificationsEmailService();
        $template = KernelMailTemplates::getChangePasswordNotificationTemplate($account);
        $notice = $emailService->create(
            $template,
            $account->email
        );
        $emailService->send($notice);

        return $result;
    }

    /**
     * Изменить password_hash пользователя (используется при подтверждении дейтсвия смены пароля)
     * @param AccountsAR $account
     * @param $new_password_hash
     * @return bool
     */
    public function changePasswordHash(AccountsAR $account, $new_password_hash) {
        $account->password_hash = $new_password_hash;
        return $account->save(false);
    }

    /**
     * Генерация password_hash для пароля
     * @param $password
     * @return false|string|null
     */
    public static function generatePasswordHash($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }

    /**
     * Смена username пользователя
     * @param AccountsAR $account
     * @param $useName
     * @throws \Exception
     */
    public function changeUsername(AccountsAR $account, $useName){
        if($account->username == $useName) {
            return;
        }
        $account->refresh();
        $account->username = $useName;
        $account->save(false);

        $emailService = new NotificationsEmailService();
        $template = KernelMailTemplates::getChangeLoginNotificationTemplate($account);
        $notice = $emailService->create(
            $template,
            $account->email
        );
        $emailService->send($notice);
    }

    /**
     * Смена email пользователя (пряма, используются при спене emial сразу после регистрации)
     * @param AccountsAR $account
     * @param $new_email
     */
    public function changeEmail(AccountsAR $account, $new_email){
        if($account->email == $new_email) {
            return;
        }
        $account->refresh();
        $account->email = $new_email;
        $account->save(false);
    }


    /**
     * Смена auth_secure_type пользователя
     * @param AccountsAR $account
     * @param $authMode
     * @return AccountsAR
     * @throws \Exception
     */
    public function changeAuthMode(AccountsAR $account, $authMode){
        $account->refresh();
        $account->auth_secure_type = $authMode;
        $account->save(false);
        return $account;
    }

    /**
     * Смена auth_secure_type пользователя
     * @param AccountsAR $account
     * @param $authMode
     * @return false|AccountsAR
     * @throws \Exception
     */
    public function changeRole(AccountsAR $account, $role){
        if(empty(AccountsAR::getRoles($role))) {
            return false;
        }
        $account->role = $role;
        $account->save(false);
        return $account;
    }

    /**
     * Смена изображения пользователя
     * @param AccountsAR $account
     * @param $avatarObjId
     * @return AccountsAR
     */
    public function changeAvatar(AccountsAR $account, $avatarObjId){
        $account->refresh();
        $account->avatar_obj_id = $avatarObjId;
        $account->save(false);
        return $account;
    }

    /**
     * Удаление изображения пользователя
     * @param AccountsAR $account
     * @return AccountsAR
     */
    public function removeAvatar(AccountsAR $account){
        $account->refresh();
        $account->avatar_obj_id = null;
        $account->save(false);
        return $account;
    }
}
