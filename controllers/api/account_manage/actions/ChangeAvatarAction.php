<?php

namespace pancakes\accounts\controllers\api\account_manage\actions;

use pancakes\accounts\controllers\validators\ChangeAvatarForm;
use pancakes\accounts\repository\responseModels\ManageAccountsModel;
use pancakes\accounts\services\AccountsService;
use pancakes\filestorage\services\FileManagerService;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\rest\RestAction;
use pancakes\kernel\base\rest\RestController;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * @property RestController $controller
 */
class ChangeAvatarAction extends RestAction
{
    public function init()
    {
        $this->modelClass = ManageAccountsModel::class;
    }

    /**
     * @param $publicKey
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function run($publicKey)
    {
        $accountService = new AccountsService(new $this->modelClass());
        try {
            $account = $accountService->getAccountByPublicKey($publicKey);
        } catch (ObjectNotFoundException $e) {
            throw new NotFoundHttpException(Yii::t('package-accounts', 'Пользователь не найден'));
        }

        $changeAvatarForm = new ChangeAvatarForm();
        $changeAvatarForm->avatarFile = UploadedFile::getInstanceByName('avatarFile');

        if(!$changeAvatarForm->validate()) {
            return $changeAvatarForm;
        }

        $tmpFilesPath = $changeAvatarForm->avatarFile->tempName;
        $service = new FileManagerService();
        $file = $service->uploadFile($tmpFilesPath, $account->id, env('ACCOUNTS_S3_BUCKET_AVATARS'), 200, 200);
        return $accountService->changeAvatar($account, $file->id);
    }
}
