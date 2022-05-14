<?php
namespace pancakes\accounts\controllers\validators;

use pancakes\kernel\base\FormModel;
use Yii;

class SignInForm extends FormModel
{
    public $account;
    public $password;
    public $secure_code;
    public $remember_me = true;

    /**
     * @inheritdoc
     */
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account', 'password'], 'required'],
            [['account', 'password', 'secure_code'], 'string'],
            ['remember_me', 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'account' => Yii::t('package-accounts', 'Адрес электронной почты'),
            'password' => Yii::t('package-accounts', 'Пароль'),
            'secure_code' => Yii::t('package-accounts', 'Код безопасности'),
            'remember_me' => Yii::t('package-accounts', 'Запомнить меня')
        ];
    }

    public function attributePlaceholders()
    {
        return [
            'account' => Yii::t('package-accounts', 'Введите адрес электронной почты'),
            'password' => Yii::t('package-accounts', 'Введите пароль'),
        ];
    }

    public function addFailAuthError($message){
        $this->addError('password', $message);
    }

    public function addIncorrectSecureCodeError($message){
        $this->addError('secure_code', $message);
    }
}
