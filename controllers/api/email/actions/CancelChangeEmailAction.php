<?php

namespace pancakes\accounts\controllers\api\email\actions;

use Exception;
use pancakes\accounts\services\AccountsEmailManageService;
use pancakes\accounts\services\AccountsService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use pancakes\kernel\base\ResultMessageModel;
use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class CancelChangeEmailAction extends RestAction
{
    public function init()
    {
        $this->modelClass = ResultMessageModel::class;
    }

    /**
     * @param $token
     * @return mixed
     * @throws Exception
     */
    public function run()
    {
        $service = new AccountsService();
        try {
            $account = $service->getAccountById(\Yii::$app->user->id);
        } catch (ObjectNotFoundException $e) {
            throw new NotFoundHttpException(\Yii::t('package-accounts', 'Пользователь не найден'));
        }

        if(!in_array($account->status_of_email_confirm, [$account::EMAIL_CHANGE_NOT_CONFIRM, $account::EMAIL_NEW_NOT_CONFIRM])) {
            return new $this->modelClass(false, Yii::t('package-accounts', 'Ваш аккаунт не нуждается в отмене запроса на изменение адреса электронной почты.'));
        }

        $emailManageAccountsService = new AccountsEmailManageService();
        $emailManageAccountsService->cancelChangeEmailAccount($account);
        return new $this->modelClass(true, Yii::t('package-accounts', 'Запрос на смену адреса электронной почты отменен'));
    }
}
