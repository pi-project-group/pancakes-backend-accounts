<?php

namespace pancakes\accounts\controllers\api\account_manage\actions;

use pancakes\accounts\repository\responseModels\ManageAccountsEmailsLogModel;
use pancakes\accounts\repository\search\AccountsEmailsSearchModel;
use pancakes\accounts\services\AccountsService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use Yii;
use yii\web\NotFoundHttpException;

class GetAccountEmailsAction extends RestAction
{
    public function init()
    {
        $this->modelClass = ManageAccountsEmailsLogModel::class;
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

        $searchModel = new AccountsEmailsSearchModel(new $this->modelClass());
        $searchModel->accountId = $account->id;

        return $searchModel->getActiveDataProvider();
    }
}
