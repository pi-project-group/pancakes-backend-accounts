<?php

namespace pancakes\accounts\controllers\api\account\actions;

use Exception;
use pancakes\accounts\controllers\validators\SignUpForm;
use pancakes\accounts\repository\responseModels\AuthenticationModel;
use pancakes\accounts\services\AuthenticationService;
use pancakes\kernel\base\rest\RestAction;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\ForbiddenHttpException;

class SignUpAction extends RestAction
{
    public function init()
    {
        $this->modelClass = AuthenticationModel::class;
        $this->formClass = SignUpForm::class;
    }

    /**
     * @return mixed
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function run()
    {
        if(!env('ACCOUNT_SIGNUP_ACTION_ENABLE')){
            throw new ForbiddenHttpException(Yii::t('package-accounts', 'Регистрация временно приостановлена'));
        }

        /** @var $form SignUpForm */
        $form = new $this->formClass();
        $form->load(Yii::$app->getRequest()->getBodyParams(), '');
        if(!$form->validate()){
            return $form;
        }

        $service = new AuthenticationService(new $this->modelClass());
        return $service->createAccount($form->login, $form->password, Yii::$app->request, true);
    }
}
