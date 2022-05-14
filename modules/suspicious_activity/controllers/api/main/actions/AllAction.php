<?php

namespace pancakes\accounts\modules\suspicious_activity\controllers\api\main\actions;

use pancakes\accounts\modules\suspicious_activity\repository\responseModels\AccountsSuspiciousActivityModel;
use pancakes\accounts\modules\suspicious_activity\repository\search\AccountsSuspiciousActivitySearchModel;
use pancakes\kernel\base\rest\RestAction;
use Yii;

/**
* All action
*/
class AllAction extends RestAction
{
    public function init()
    {
        $this->modelClass = AccountsSuspiciousActivityModel::class;
        $this->searchClass = AccountsSuspiciousActivitySearchModel::class;
    }

    /**
     * @return mixed
     */
    public function run()
    {
        /** @var $searchModel AccountsSuspiciousActivitySearchModel */
        $searchModel = new $this->searchClass(new $this->modelClass());
        $searchModel->loadParams(Yii::$app->request->queryParams);
        return $searchModel->getActiveDataProvider();
    }
}
