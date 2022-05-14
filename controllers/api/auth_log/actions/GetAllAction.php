<?php

namespace pancakes\accounts\controllers\api\auth_log\actions;

use pancakes\accounts\repository\responseModels\ManageAccountsAuthLogModel;
use pancakes\accounts\repository\search\AccountsAuthLogSearchModel;
use pancakes\accounts\services\AccountsAuthLogService;
use pancakes\kernel\base\rest\RestAction;
use Yii;

/**
* All action
*/
class GetAllAction extends RestAction
{
    public function init()
    {
        $this->modelClass = ManageAccountsAuthLogModel::class;
    }

    /**
     * @return mixed
     */
    public function run()
    {
        $searchModel = new AccountsAuthLogSearchModel(new $this->modelClass());
        $searchModel->load(Yii::$app->request->queryParams, '');

        $service = new AccountsAuthLogService();
        return $service->getAccountAuthLogDataProvider($searchModel);
    }
}
