<?php

namespace pancakes\accounts\controllers\api\account\actions;

use pancakes\accounts\services\AccountsService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use pancakes\kernel\base\ResultMessageModel;
use pancakes\accounts\modules\confirmation\controllers\validators\ActionConfirmFrom;
use pancakes\accounts\modules\confirmation\services\ConfirmActionsService;
use Yii;
use yii\web\NotFoundHttpException;

class ChangePasswordConfirmAction extends RestAction
{
    public function init()
    {
        $this->modelClass = ResultMessageModel::class;
        $this->formClass = ActionConfirmFrom::class;
    }

    /**
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function run()
    {
        /** @var $form ActionConfirmFrom */
        $form = new $this->formClass();
        $form->loadParams(Yii::$app->request->bodyParams);
        if(!$form->validate()){
            return $form;
        }

        $accountsService = new AccountsService();
        try {
            $account =  $accountsService->getAccountById(\Yii::$app->user->id);
        } catch (ObjectNotFoundException $e) {
            throw new NotFoundHttpException(\Yii::t('package-accounts', 'Пользователь не найден'));
        }

        $confirmActionsService = new ConfirmActionsService();
        $confirmActionsModel = $confirmActionsService->find($form->public_key, $account->id);
        if(empty($confirmActionsModel)) {
            $form->addPublicKeyError(Yii::t('package-accounts', 'Не верный публичный ключ для подтверждения действия'));
            return $form;
        }

        $attempts_left = 3 - $confirmActionsModel->attempts;
        if($attempts_left <= 0) {
            throw new NotFoundHttpException(Yii::t('package-accounts', 'Запрос на подтверждение действия не существует или устарел.'));
        }

        if($confirmActionsService->confirmByCode($confirmActionsModel, $form->secret_key)) {
            $new_password_hash = json_decode($confirmActionsModel->data, true)['new_password_hash'];
            if(empty($new_password_hash)){
                return new $this->modelClass(false, Yii::t('package-accounts', 'Не удалось изменить данные'));
            }
            $accountsService->changePasswordHash($account, $new_password_hash);
            return new $this->modelClass(
                true,
                Yii::t('package-accounts', 'Пароль успешно изменен'),
                Yii::t('package-accounts', 'Ваш пароль был успешно изменен на новый.')
            );
        }

        $form->addSecretKeyError(Yii::t('package-accounts', 'Не верный проверочный код. Осталось попыток: {n}', ['n' => $attempts_left]));
        return $form;

    }
}
