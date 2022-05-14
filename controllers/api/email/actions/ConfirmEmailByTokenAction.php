<?php

namespace pancakes\accounts\controllers\api\email\actions;

use Exception;
use pancakes\accounts\services\AccountsEmailManageService;
use pancakes\kernel\base\rest\RestAction;
use pancakes\kernel\base\ResultMessageModel;
use Yii;

class ConfirmEmailByTokenAction extends RestAction
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
            $account = $service->confirmEmailAccount($account);
            return new $this->modelClass(true, Yii::t('common', 'Email {0} успешно подтвержден.', [$account->email]));
        } catch (Exception $e) {
            return new $this->modelClass(false, Yii::t('package-accounts', 'Мы сожалеем. Токен не существиет либо устарел.'));
        }
    }
}
