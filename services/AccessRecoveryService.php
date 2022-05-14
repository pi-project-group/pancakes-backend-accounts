<?php

namespace pancakes\accounts\services;

use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\utils\OtherUtils;
use pancakes\accounts\base\AccountIdentity;
use pancakes\accounts\mail\KernelMailTemplates;
use pancakes\accounts\repository\ar\AccountsAccessRecoveryAR;
use pancakes\notifications\modules\emails\services\NotificationsEmailService;
use yii\db\Exception;

/**
 * Сервис восстановление доступа к аккаунтам
 */
class AccessRecoveryService
{
    protected $dataGateway;

    public function __construct(AccountsAccessRecoveryAR $dataGateway = null)
    {
        $this->dataGateway = !empty($dataGateway) ? $dataGateway : new AccountsAccessRecoveryAR();
    }

    /**
     * Получение identity по email
     * @param $email
     * @return AccountIdentity
     * @throws ObjectNotFoundException
     */
    public function getAccountIdentityByEmail($email){
        /** @var $account AccountIdentity */
        $account = AccountIdentity::queryFindUser(null, $email)->one();
        if (empty($account)) {
            throw new ObjectNotFoundException($email);
        }
        return $account;
    }

    /**
     * Получение identity по токену восстановления доступа
     * @param $token
     * @return AccountIdentity
     * @throws ObjectNotFoundException
     */
    public function getAccountIdentityByRecoveryAccessToken($token){
        /** @var $account AccountIdentity */
        $account = AccountsAccessRecoveryAR::getAccountByToken($token);
        if (empty($account)) {
            throw new ObjectNotFoundException($token);
        }
        return $account;
    }

    /**
     * Запрос на восстановление доступа по переданному identity
     * @param AccountIdentity $account
     * @throws \Exception
     */
    public function requestToRestoreAccess(AccountIdentity $account){
        AccountsAccessRecoveryAR::setAllOutdatedStatuses($account->id);
        $token = new AccountsAccessRecoveryAR();
        $token->user_id = $account->id;
        $token->token = OtherUtils::generatePublicKey();
        $token->status = $token::STATUS_NOT_USED;
        $token->save(false);

        $emailService = new NotificationsEmailService();
        $template = KernelMailTemplates::getRecoveryAccountAccessTemplate($account, $token);
        $notice = $emailService->create(
            $template,
            $account->email
        );
        $emailService->send($notice);
    }

    /**
     * Изменение пароля пользователя по токену
     * @param $token
     * @param $newPassword
     * @return bool
     * @throws ObjectNotFoundException
     * @throws Exception
     */
    public function changePasswordByToken($token, $newPassword) {
        $account = $this->getAccountIdentityByRecoveryAccessToken($token);
        $accountService = new AccountsService();
        $this->dataGateway::setUsedStatus($token);
        return $accountService->changePassword($account, $newPassword);
    }
}
