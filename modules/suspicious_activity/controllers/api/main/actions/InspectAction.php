<?php

namespace pancakes\accounts\modules\suspicious_activity\controllers\api\main\actions;

use pancakes\accounts\modules\suspicious_activity\controllers\validators\AccountsSuspiciousActivityInspectFrom;
use pancakes\accounts\modules\suspicious_activity\repository\responseModels\AccountsSuspiciousActivityModel;
use pancakes\accounts\modules\suspicious_activity\services\AccountsSuspiciousActivityService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use Yii;
use yii\web\NotFoundHttpException;

/**
* Edit action
*/
class InspectAction extends RestAction
{
    public function init()
    {
        $this->modelClass = AccountsSuspiciousActivityModel::class;
        $this->formClass = AccountsSuspiciousActivityInspectFrom::class;
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function run($id)
    {
        $accountsSuspiciousActivityService = new AccountsSuspiciousActivityService(new $this->modelClass());
        try {
            $activityModel = $accountsSuspiciousActivityService->findById($id);
        } catch (ObjectNotFoundException $e) {
            throw new NotFoundHttpException(Yii::t('package-accounts', 'Объект не найден'));
        }

        /** @var $form AccountsSuspiciousActivityInspectFrom */
        $form = new $this->formClass();
        $form->loadParams(Yii::$app->request->bodyParams);
        if(!$form->validate()){
            return $form;
        }

        return $accountsSuspiciousActivityService->inspect($activityModel, $form);
    }
}
