<?php
namespace pancakes\accounts\controllers\validators;

use pancakes\accounts\repository\models\ChangeAvatarModel;
use Yii;
use yii\web\UploadedFile;

class ChangeAvatarForm extends ChangeAvatarModel
{
    /**
     * @var UploadedFile
     */
    public $avatarFile;

    /**
     * @inheritdoc
     */
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['avatarFile'], 'required'],
            [['avatarFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'avatarFile' => Yii::t('package-accounts', 'Файл изображения')
        ];
    }
}
