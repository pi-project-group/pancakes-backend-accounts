<?php

namespace pancakes\accounts\controllers\api\account\actions;

use pancakes\accounts\controllers\validators\SignInForm;
use pancakes\accounts\exceptions\AuthFailException;
use pancakes\accounts\exceptions\SecurityCodeRequiredException;
use pancakes\accounts\repository\responseModels\AuthenticationModel;
use pancakes\accounts\services\AuthenticationService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use Yii;

class GetTokenAction extends RestAction
{
    public function init()
    {
        $this->modelClass = AuthenticationModel::class;
        $this->formClass = SignInForm::class;
    }

    /**
     * @return mixed
     */
    public function run()
    {
        /** @var $form SignInForm */
        $form = new $this->formClass();
        $form->loadParams(Yii::$app->request->bodyParams);
        if(!$form->validate()){
            return $form;
        }

        $service = new AuthenticationService(new $this->modelClass());
        try {
            return $service->authAccount($form->account, $form->password, $form->secure_code, Yii::$app->request);
        } catch (ObjectNotFoundException $e) {
            $form->addFailAuthError($e->getMessage());
            return $form;
        } catch (AuthFailException $e) {
            $form->addFailAuthError($e->getMessage());
            return $form;
        } catch (SecurityCodeRequiredException $e) {
            $form->addIncorrectSecureCodeError($e->getMessage());
            return $form;
        }
    }
}
