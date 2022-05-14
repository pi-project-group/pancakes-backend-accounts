<?php

namespace pancakes\accounts\controllers\api\account\actions;

use pancakes\accounts\controllers\validators\ChangeAuthSecureTypeForm;
use pancakes\accounts\repository\responseModels\AccountsPrivateModel;
use pancakes\accounts\services\AccountsService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use pancakes\accounts\modules\confirmation\repository\responseModels\ConfirmActionsModel;
use pancakes\accounts\modules\confirmation\services\ConfirmActionsService;
use pancakes\notifications\modules\emails\services\NotificationsEmailService;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class ChangeAuthSecureTypeAction extends RestAction
{
    public function init()
    {
        $this->modelClass = ConfirmActionsModel::class;
        $this->formClass = ChangeAuthSecureTypeForm::class;
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
            $account = $service->getAccountById(\Yii::$app->user->id);
        } catch (ObjectNotFoundException $e) {
            throw new NotFoundHttpException(\Yii::t('package-accounts', 'Пользователь не найден'));
        }

        if($account->status_of_email_confirm == $account::EMAIL_FIRST_NOT_CONFIRM){
            throw new ForbiddenHttpException(Yii::t('package-accounts', 'Для изменения необходимо подтвердить адрес электронной почты.'));
        }

        /** @var $form ChangeAuthSecureTypeForm */
        $form = new $this->formClass();
        $form->loadParams(Yii::$app->request->bodyParams);
        if (!$form->validate()) {
            return $form;
        }

        $confirmActionsService = new ConfirmActionsService(new $this->modelClass());
        $confirmActionModel = $confirmActionsService->create($form->loadParams, Yii::$app->user->id);
        $notificationsEmailService = new NotificationsEmailService();
        $notificationsEmailService->createEmailNotification(
            'confirm-edit-account',
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
