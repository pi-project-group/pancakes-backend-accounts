<?php
namespace pancakes\accounts\controllers\validators;

use pancakes\accounts\repository\ar\AccountsAR;
use pancakes\kernel\base\validators\ReCaptchaValidator;
use pancakes\accounts\repository\models\AccountsAccessRecoveryModel;
use Yii;

class AccountsAccessRecoveryForm extends AccountsAccessRecoveryModel
{
    public $email;
    public $verify_code;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            [['email'], 'required'],
            ['email', 'email'],
            ['verify_code', 'string'],
            ['verify_code', ReCaptchaValidator::class, 'skipOnEmpty' => false, 'when' => function() {
                return empty($this->errors);
            }],
            ['email', 'exist', 'skipOnError' => true,
                'targetClass' => AccountsAR::class,
                'filter' => ['status' => AccountsAR::STATUS_ACTIVE],
                'message' => Yii::t('package-accounts', 'Пользователь не найден или заблокирован.'),
                'when' => function($model) {
                    return !$model->hasErrors('verify_code');
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('package-accounts', 'Адрес электронной почты'),
            'verify_code' => Yii::t('package-accounts', 'Проверочный код'),
        ];
    }

    public function attributePlaceholders()
    {
        return [
            'email' => Yii::t('package-accounts', 'Ваш адрес электронной почты'),
        ];

    }
}
