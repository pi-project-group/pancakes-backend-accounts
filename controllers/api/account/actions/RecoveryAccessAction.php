<?php

namespace pancakes\accounts\controllers\api\account\actions;

use Exception;
use pancakes\accounts\controllers\validators\AccountsAccessRecoveryForm;
use pancakes\accounts\services\AccessRecoveryService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use pancakes\kernel\base\ResultMessageModel;
use Yii;

class RecoveryAccessAction extends RestAction
{
    public function init()
    {
        $this->modelClass = ResultMessageModel::class;
        $this->formClass = AccountsAccessRecoveryForm::class;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function run()
    {
        /** @var $form AccountsAccessRecoveryForm */
        $form = new $this->formClass();
        $form->loadParams(Yii::$app->request->bodyParams);
        if(!$form->validate()){
            return $form;
        }

        $accessRecoveryService = new AccessRecoveryService();
        try {
            $account = $accessRecoveryService->getAccountIdentityByEmail($form->email);
            $accessRecoveryService->requestToRestoreAccess($account);
        } catch (ObjectNotFoundException $e) {
        }

        return new $this->modelClass(
            true,
            Yii::t(
                'package-accounts',
                'На {email} отправлены дальшейшие инструкции для восстановления доступа к ресурсу',
                ['email' => $form->email]
            )
        );
    }
}
