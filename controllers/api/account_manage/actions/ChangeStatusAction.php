<?php

namespace pancakes\accounts\controllers\api\account_manage\actions;

use pancakes\accounts\controllers\validators\AccountChangeStatusFrom;
use pancakes\accounts\repository\responseModels\ManageAccountsStatusLogModel;
use pancakes\accounts\services\AccountsService;
use pancakes\accounts\services\AccountsStatusService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class ChangeStatusAction extends RestAction
{
    public function init()
    {
        $this->modelClass = ManageAccountsStatusLogModel::class;
        $this->formClass = AccountChangeStatusFrom::class;
    }

    /**
     * @param $publicKey
     * @return mixed
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function run($publicKey)
    {
        $accountService = new AccountsService();
        try {
            $account = $accountService->getAccountByPublicKey($publicKey);
        } catch (ObjectNotFoundException $e) {
            throw new NotFoundHttpException(Yii::t('package-accounts', 'Пользователь не найден'));
        }

        if( $account->id === Yii::$app->user->id) {
            throw new ForbiddenHttpException(Yii::t('package-accounts', 'Невозможно поменять статус самому себе'));
        }

        /** @var $form AccountChangeStatusFrom */
        $form = new $this->formClass();
        $form->load(Yii::$app->getRequest()->getBodyParams(), '');
        if(!$form->validate()){
            return $form;
        }

        $service = new AccountsStatusService(new $this->modelClass());
        return $service->changeAccountStatus($form, $account);
    }
}
