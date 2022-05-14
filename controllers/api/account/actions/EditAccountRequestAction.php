<?php

namespace pancakes\accounts\controllers\api\account\actions;

use pancakes\accounts\controllers\validators\ChangeAccountByActionConfirmForm;
use pancakes\accounts\services\AccountsService;
use pancakes\accounts\services\AccountsEmailManageService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use pancakes\accounts\modules\confirmation\repository\responseModels\ConfirmActionsModel;
use pancakes\accounts\modules\confirmation\services\ConfirmActionsService;
use pancakes\notifications\modules\emails\services\NotificationsEmailService;
use Yii;
use yii\web\NotFoundHttpException;

class EditAccountRequestAction extends RestAction
{
    public function init()
    {
        $this->modelClass = ConfirmActionsModel::class;
        $this->formClass = ChangeAccountByActionConfirmForm::class;
    }

    /**
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function run()
    {
        $accountsService = new AccountsService();
        try {
            $account =  $accountsService->getAccountById(\Yii::$app->user->id);
        } catch (ObjectNotFoundException $e) {
            throw new NotFoundHttpException(\Yii::t('package-accounts', 'Пользователь не найден'));
        }

        $form = new ChangeAccountByActionConfirmForm($account);
        $form->loadParams(Yii::$app->request->bodyParams);
        if(!$form->validate()){
            return $form;
        }

        $service = new ConfirmActionsService(new $this->modelClass());
        if($account->status_of_email_confirm == $account::EMAIL_FIRST_NOT_CONFIRM) {
            // Если аккаунт не подтвержденный сразу меняем данные
            $accountsService->changeUsername($account, $form->username);
            $accountsService->changeEmail($account, $form->email);
            $confirmActionModel = $service->create($form->loadParams, $account->id, true);
        } else {
            // Если подтвержденный то только чз проверочный код
            $confirmActionModel = $service->create($form->loadParams, $account->id);
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
        }

        return $confirmActionModel;
    }
}
