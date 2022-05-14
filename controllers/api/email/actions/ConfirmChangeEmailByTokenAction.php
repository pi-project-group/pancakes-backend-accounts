<?php

namespace pancakes\accounts\controllers\api\email\actions;

use Exception;
use pancakes\accounts\services\AccountsEmailManageService;
use pancakes\kernel\base\rest\RestAction;
use pancakes\kernel\base\ResultMessageModel;
use Yii;

class ConfirmChangeEmailByTokenAction extends RestAction
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
    public function run($token)
    {
        try {
            $service = new AccountsEmailManageService();
            $account = $service->findAccountByEmailConfirmToken($token);
            $account = $service->confirmChangeEmailAccount($account);
            return new $this->modelClass(true, Yii::t('common', 'Вы подтвердили смену адреса электронной почты.', [$account->email]));
        } catch (Exception $e) {
            return new $this->modelClass(false, Yii::t('package-accounts', 'Мы сожалеем. Токен не существиет либо устарел.'));
        }
    }
}
