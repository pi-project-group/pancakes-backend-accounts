<?php

namespace pancakes\accounts\controllers\api\account\actions;

use pancakes\accounts\controllers\validators\ChangePasswordForm;
use pancakes\accounts\services\AccountsService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use pancakes\accounts\modules\confirmation\repository\responseModels\ConfirmActionsModel;
use pancakes\accounts\modules\confirmation\services\ConfirmActionsService;
use pancakes\notifications\modules\emails\services\NotificationsEmailService;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Действие отправляет запрос на смену пароля авторизированного пользователя. После отправки запроса, на email
 * пользователя должно придти письмно с кодом подтверждения действия, который нужно отправить в паре с `public_key`
 * на v1/accounts/change-password-confirm
 */
class ChangePasswordAction extends RestAction
{
    public function init()
    {
        $this->modelClass = ConfirmActionsModel::class;
        $this->formClass = ChangePasswordForm::class;
    }

    /**
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function run()
    {
        $service = new AccountsService();
        try {
            $account =  $service->getAccountById(\Yii::$app->user->id);
        } catch (ObjectNotFoundException $e) {
            throw new NotFoundHttpException(\Yii::t('package-accounts', 'Пользователь не найден'));
        }

        /** @var $form ChangePasswordForm */
        $form = new $this->formClass($account);
        $form->loadParams(Yii::$app->request->bodyParams);
        if(!$form->validate()){
            return $form;
        }

        $service = new ConfirmActionsService(new $this->modelClass());
        $confirmActionModel = $service->create(['new_password_hash' => AccountsService::generatePasswordHash($form->new_password)], $account->id);

        $notificationsEmailService = new NotificationsEmailService();
        $notificationsEmailService->createEmailNotification(
            'confirm-change-password',
            [
                'username' => $account->username,
                'code' => $confirmActionModel->code
            ],
            $account->email,
            true
        );

        return $confirmActionModel;
    }
}
