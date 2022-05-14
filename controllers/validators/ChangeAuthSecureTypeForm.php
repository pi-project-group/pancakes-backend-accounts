<?php
namespace pancakes\accounts\controllers\validators;

use pancakes\accounts\repository\ar\AccountsAR;
use pancakes\kernel\base\FormModel;
use Yii;

class ChangeAuthSecureTypeForm extends FormModel
{
    public $auth_secure_type;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['auth_secure_type'], 'required'],
            [['auth_secure_type'], 'integer'],
            [['auth_secure_type'], 'in', 'range' => [
                AccountsAR::AUTH_SECURE_TYPE_DISABLED,
                AccountsAR::AUTH_SECURE_TYPE_BY_IP,
                AccountsAR::AUTH_SECURE_TYPE_ENABLED]
            ]

        ];
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'auth_secure_type' => Yii::t('package-accounts', 'Тип безопасной авторизации'),
        ];
    }
}
