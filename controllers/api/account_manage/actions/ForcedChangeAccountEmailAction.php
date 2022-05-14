<?php

namespace pancakes\accounts\controllers\api\account_manage\actions;

use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use pancakes\accounts\controllers\validators\ChangeEmailForm;
use pancakes\accounts\services\AccountsEmailManageService;
use pancakes\accounts\services\AccountsService;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;

class ForcedChangeAccountEmailAction extends RestAction
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

        $form = new ChangeEmailForm();
        $form->load(Yii::$app->getRequest()->getBodyParams(), '');
        if(!$form->validate()){
            return $form;
        }

        $service = new AccountsEmailManageService();
        return $service->forcedChangeAccountEmail($account, $form->new_email);
    }
}
