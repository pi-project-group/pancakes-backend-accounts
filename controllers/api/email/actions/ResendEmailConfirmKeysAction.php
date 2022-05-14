<?php

namespace pancakes\accounts\controllers\api\email\actions;

use pancakes\accounts\services\AccountsService;
use pancakes\accounts\services\AccountsEmailManageService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use pancakes\kernel\base\ResultMessageModel;
use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class ResendEmailConfirmKeysAction extends RestAction
{
    public function init()
    {
        $this->modelClass = ResultMessageModel::class;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function run()
    {
        $service = new AccountsService();
        try {
            $account = $service->getAccountById(\Yii::$app->user->id);
        } catch (ObjectNotFoundException $e) {
            throw new NotFoundHttpException(\Yii::t('package-accounts', 'Пользователь не найден'));
        }

        if($account->status_of_email_confirm == $account::EMAIL_CONFIRM_TRUE) {
            throw new HttpException(\Yii::t('package-accounts', 'Ваш аккаунт не нуждается в подтверждении.'));
        }

        $emailManageAccountsService= new AccountsEmailManageService();
        $emailManageAccountsService->validateExceptionToken($account->email_confirm_token);
        $emailManageAccountsService->generatedEmailConfirmKeys($account);
        return new $this->modelClass(true, Yii::t('package-accounts', 'Инструкции для подтверждения повторно отправлены на ваш почтовый адрес.'));
    }
}
