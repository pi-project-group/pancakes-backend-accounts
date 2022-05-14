<?php

namespace pancakes\accounts\controllers\api\account\actions;

use pancakes\accounts\services\AccountsService;
use pancakes\filestorage\repository\responseModels\FilestorageObjectsModel;
use pancakes\filestorage\services\FileManagerService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use pancakes\kernel\base\rest\RestController;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * @property RestController $controller
 */
class ChangeAvatarAction extends RestAction
{
    public function init()
    {
        $this->modelClass = FilestorageObjectsModel::class;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function run()
    {
        $service = new AccountsService(new $this->modelClass());
        try {
            $account = $service->getAccountById(\Yii::$app->user->id);
        } catch (ObjectNotFoundException $e) {
            throw new NotFoundHttpException(\Yii::t('package-accounts', 'Пользователь не найден'));
        }

        $instances = UploadedFile::getInstanceByName('avatarFile');
        $tmpFilesPaths = $instances['tempName'];
        $service = new FileManagerService(new $this->modelClass());
        $service->uploadImageFile($tmpFilesPaths, $account->id, 'avatars', 200, null);
    }
}
