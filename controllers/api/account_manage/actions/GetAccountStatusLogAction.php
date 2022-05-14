<?php

namespace pancakes\accounts\controllers\api\account_manage\actions;

use pancakes\accounts\repository\responseModels\ManageAccountsStatusLogModel;
use pancakes\accounts\repository\search\AccountsStatusLogSearchModel;
use pancakes\accounts\services\AccountsService;
use pancakes\accounts\services\AccountsStatusService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use Yii;
use yii\web\NotFoundHttpException;

class GetAccountStatusLogAction extends RestAction
{
    public function init()
    {
        $this->modelClass = ManageAccountsStatusLogModel::class;
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

        $searchModel = new AccountsStatusLogSearchModel(new $this->modelClass());
        $searchModel->accountId = $account->id;

        $service = new AccountsStatusService();
        return $service->getAccountStatusLogDataProvider($searchModel);
    }
}
