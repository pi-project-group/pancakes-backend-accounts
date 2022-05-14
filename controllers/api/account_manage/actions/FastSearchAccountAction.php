<?php

namespace pancakes\accounts\controllers\api\account_manage\actions;

use pancakes\accounts\repository\responseModels\AccountsShortModel;
use pancakes\accounts\repository\search\AccountsFastSearchModel;
use pancakes\kernel\base\rest\RestAction;
use Yii;

class FastSearchAccountAction extends RestAction
{
    public function init()
    {
        $this->modelClass = AccountsShortModel::class;
        $this->searchClass = AccountsFastSearchModel::class;
    }

    /**
     * @return mixed
     */
    public function run()
    {
        /** @var $searchModel AccountsFastSearchModel */
        $searchModel = new $this->searchClass(new $this->modelClass());
        $searchModel->loadParams(Yii::$app->request->queryParams);
        return $searchModel->getActiveDataProvider();
    }
}
