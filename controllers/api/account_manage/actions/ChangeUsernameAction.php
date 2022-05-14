<?php

namespace pancakes\accounts\controllers\api\account_manage\actions;

use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use pancakes\accounts\controllers\validators\AccountChangeStatusFrom;
use pancakes\accounts\controllers\validators\ChangeUsernameManageForm;
use pancakes\accounts\services\AccountsService;
use pancakes\accounts\services\AccountsStatusService;
use pancakes\accounts\services\ManageUsernameAccountsService;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class ChangeUsernameAction extends RestAction
{
    public function init()
    {
        $this->modelClass = null;
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

        $form = new ChangeUsernameManageForm();
        $form->load(Yii::$app->getRequest()->getBodyParams(), '');
        if(!$form->validate()){
            return $form;
        }

        $service = new ManageUsernameAccountsService();
        return $service->changeAccountUsername($account, $form);
    }
}
