<?php

namespace pancakes\accounts\controllers\api\auth_log\actions;

use pancakes\accounts\repository\responseModels\ManageAccountsAuthLogModel;
use pancakes\accounts\repository\search\AccountsAuthLogSearchModel;
use pancakes\accounts\services\AccountsAuthLogService;
use pancakes\accounts\services\AccountsService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use Yii;
use yii\web\NotFoundHttpException;

class GetAllByUserAction extends RestAction
{
    public function init()
    {
        $this->modelClass = ManageAccountsAuthLogModel::class;
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

        $searchModel = new AccountsAuthLogSearchModel(new $this->modelClass());
        $searchModel->accountId = $account->id;

        $service = new AccountsAuthLogService();
        return $service->getAccountAuthLogDataProvider($searchModel);
    }
}
