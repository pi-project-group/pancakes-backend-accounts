<?php
namespace pancakes\accounts\repository\models;

use pancakes\kernel\base\FormModel;
use yii\web\UploadedFile;

class ChangeAvatarModel extends FormModel
{
    /**
     * @var UploadedFile
     */
    public $avatarFile;
}
