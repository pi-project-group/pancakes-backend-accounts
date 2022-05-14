<?php

namespace pancakes\accounts\controllers\api\account_manage\actions;

use Exception;
use pancakes\accounts\controllers\validators\SignUpForm;
use pancakes\accounts\repository\responseModels\ManageAccountsModel;
use pancakes\accounts\services\AuthenticationService;
use pancakes\kernel\base\rest\RestAction;
use Yii;

class CreateAccountAction extends RestAction
{
    public function init()
    {
        $this->modelClass = ManageAccountsModel::class;
        $this->formClass = SignUpForm::class;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function run()
    {
        /** @var $form SignUpForm */
        $form = new $this->formClass();
        $form->loadParams(Yii::$app->request->bodyParams);
        if(!$form->validate()){
            return $form;
        }

        $service = new AuthenticationService(new $this->modelClass());
        Yii::$app->response->setStatusCode(201);
        return $service->createAccount($form->email, $form->password, Yii::$app->request);
    }
}
