<?php

namespace pancakes\accounts\services;

use pancakes\accounts\repository\ar\AccountsAR;
use pancakes\accounts\repository\ar\AccountsUsernameLogAR;
use pancakes\accounts\repository\models\ChangeUsernameModel;
use pancakes\accounts\repository\search\AccountsUsernameLogSearchModel;
use yii\data\ActiveDataProvider;

/**
 * @property AccountsUsernameLogAR $dataGateway
 */
class ManageUsernameAccountsService
{
    protected $dataGateway;

    public function __construct(AccountsUsernameLogAR $dataGateway = null)
    {
        $this->dataGateway = !empty($dataGateway) ? $dataGateway : new AccountsUsernameLogAR();
    }

    /**
     * @param AccountsUsernameLogSearchModel $searchModel
     * @return ActiveDataProvider
     */
    public function getAccountUsernameLogDataProvider(AccountsUsernameLogSearchModel $searchModel) {
        return $searchModel->getActiveDataProvider();
    }

    public function changeAccountUsername(AccountsAR $account, ChangeUsernameModel $form, $emailNotification = false) {
        $account->refresh();

        $this->addUsernameChangeLog($account->id, $account->username, $form->newUsername, $form->comment);
        $account->username = $form->newUsername;
        $account->save(false);

        return $account;
    }

    protected function addUsernameChangeLog($accountId, $beforeUserName, $newUsername, $comment) {
        $log = new AccountsUsernameLogAR();
        $log->account_id = $accountId;
        $log->before_username = $beforeUserName;
        $log->new_username = $newUsername;
        $log->comment = $comment;
        $log->save(false);
    }

    /**
     * Проверяет существование аккаунта по логину
     * @param $login
     * @return bool
     */
    public function isAccountExists($login){
        return AccountsAR::queryFindAccountForLogin($login)->exists();
    }
}
