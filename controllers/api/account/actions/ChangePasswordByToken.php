<?php

namespace pancakes\accounts\controllers\api\account\actions;

use common\base\ResultBaseModel;
use Exception;
use pancakes\accounts\controllers\validators\ChangePasswordByTokenForm;
use pancakes\accounts\services\AccessRecoveryService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use Yii;
use yii\base\InvalidConfigException;

class ChangePasswordByToken extends RestAction
{
    public function init()
    {
        $this->modelClass = ResultBaseModel::class;
        $this->formClass = ChangePasswordByTokenForm::class;
    }

    /**
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function run()
    {
        /** @var $form ChangePasswordByTokenForm */
        $form = new $this->formClass();
        $form->loadParams(Yii::$app->request->bodyParams);
        if(!$form->validate()){
            return $form;
        }

        $accessRecoveryService = new AccessRecoveryService();
        try {
            $accessRecoveryService->changePasswordByToken($form->token, $form->new_password);
            return new $this->modelClass(
                true,
                Yii::t(
                    'package-accounts',
                    'Пароль успешно изменен'
                )
            );
        } catch (ObjectNotFoundException $e) {
            return new $this->modelClass(
                false,
                Yii::t(
                    'package-accounts',
                    'Токен не существует или устарел',
                )
            );
        }
    }
}
