<?php

namespace pancakes\accounts\controllers\api\account_manage\actions;

use pancakes\accounts\repository\responseModels\ManageAccountsModel;
use pancakes\accounts\repository\search\AccountsSearchModel;
use pancakes\accounts\services\AccountsService;
use pancakes\kernel\base\rest\RestAction;
use Yii;

class IndexAction extends RestAction
{
    public function init()
    {
        $this->modelClass = ManageAccountsModel::class;
        $this->searchClass = AccountsSearchModel::class;
    }

    /**
     * @return mixed
     */
    public function run()
    {
        /** @var $searchModel AccountsSearchModel */
        $searchModel = new $this->searchClass(new $this->modelClass());
        $searchModel->load(Yii::$app->request->queryParams, '');

        $service = new AccountsService();
        return $service->getAccountsDataProvider($searchModel);
    }
}
