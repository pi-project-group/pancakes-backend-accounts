<?php

namespace pancakes\accounts\controllers\api\account_manage\actions;

use pancakes\accounts\repository\responseModels\ManageAccountsUsernameLogModel;
use pancakes\accounts\repository\search\AccountsUsernameLogSearchModel;
use pancakes\accounts\services\AccountsService;
use pancakes\accounts\services\ManageUsernameAccountsService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use Yii;
use yii\web\NotFoundHttpException;

class GetAccountUserNamesLogAction extends RestAction
{
    public function init()
    {
        $this->modelClass = ManageAccountsUsernameLogModel::class;
    }

    /**
     * @param $publicKey
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function run($publicKey)
    {
        $accountService = new AccountsService();
        try {
            $account = $accountService->getAccountByPublicKey($publicKey);
        } catch (ObjectNotFoundException $e) {
            throw new NotFoundHttpException(Yii::t('package-accounts', 'Пользователь не найден'));
        }

        $searchModel = new AccountsUsernameLogSearchModel(new $this->modelClass());
        $searchModel->accountId = $account->id;

        $service = new ManageUsernameAccountsService();
        return $service->getAccountUsernameLogDataProvider($searchModel);
    }
}
