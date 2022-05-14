<?php

namespace pancakes\accounts\controllers\api\account_manage\actions;

use pancakes\accounts\repository\responseModels\ManageAccountsModel;
use pancakes\accounts\services\AccountsService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use pancakes\kernel\base\rest\RestController;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * @property RestController $controller
 */
class AvatarRemoveAction extends RestAction
{
    public function init()
    {
        $this->modelClass = ManageAccountsModel::class;
    }

    /**
     * @param $publicKey
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function run($publicKey)
    {
        $accountService = new AccountsService(new $this->modelClass());
        try {
            $account = $accountService->getAccountByPublicKey($publicKey);
        } catch (ObjectNotFoundException $e) {
            throw new NotFoundHttpException(Yii::t('package-accounts', 'Пользователь не найден'));
        }
        return $accountService->removeAvatar($account);
    }
}
