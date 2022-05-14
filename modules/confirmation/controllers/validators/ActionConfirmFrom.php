<?php
namespace pancakes\accounts\modules\confirmation\controllers\validators;

use pancakes\kernel\base\FormModel;
use Yii;

class ActionConfirmFrom extends FormModel
{
    public $public_key;
    public $secret_key;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['public_key', 'secret_key'], 'required'],
            [['public_key', 'secret_key'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'public_key' => Yii::t('package-accounts', 'Публичный ключ из response'),
            'secret_key' => Yii::t('package-accounts', 'Секретный код из email')
        ];
    }

    public function addPublicKeyError($message){
        $this->addError('secret_key', $message);
    }

    public function addSecretKeyError($message){
        $this->addError('secret_key', $message);
    }
}
