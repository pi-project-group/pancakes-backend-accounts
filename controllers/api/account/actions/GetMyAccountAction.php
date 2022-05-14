<?php

namespace pancakes\accounts\controllers\api\account\actions;

use pancakes\accounts\repository\responseModels\AccountsPrivateModel;
use pancakes\accounts\services\AccountsService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use yii\web\NotFoundHttpException;

class GetMyAccountAction extends RestAction
{
    public function init()
    {
        $this->modelClass = AccountsPrivateModel::class;
    }

    /**
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function run()
    {

        $service = new AccountsService(new $this->modelClass());
        try {
            return $service->getAccountById(\Yii::$app->user->id);
        } catch (ObjectNotFoundException $e) {
            throw new NotFoundHttpException(\Yii::t('package-accounts', 'Пользователь не найден'));
        }
    }
}
