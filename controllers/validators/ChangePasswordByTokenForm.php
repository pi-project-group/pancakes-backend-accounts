<?php
namespace pancakes\accounts\controllers\validators;

use pancakes\accounts\repository\ar\AccountsAccessRecoveryAR;
use pancakes\kernel\base\FormModel;
use Yii;

class ChangePasswordByTokenForm extends FormModel
{
    public $token;
    public $new_password;
    public $new_password_repeat;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['token', 'new_password', 'new_password_repeat'], 'required'],
            ['new_password', 'string', 'min' => 6],
            ['new_password_repeat', 'compare', 'compareAttribute'=>'new_password'],
            ['token', 'validateToken']

        ];
        return $rules;
    }

    public function validateToken($attribute){
        $token = $this->$attribute;
        if(empty(AccountsAccessRecoveryAR::getAccountByToken($token))){
            $this->addError($attribute, Yii::t('package-accounts', 'Токен не существует или устарел'));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'token' => Yii::t('package-accounts', 'Токен'),
            'new_password' => Yii::t('package-accounts', 'Новый пароль'),
            'new_password_repeat' => Yii::t('package-accounts', 'Повтор нового пароля'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributePlaceholders()
    {
        return [
            'new_password' => Yii::t('package-accounts', 'Введите новый пароль'),
            'new_password_repeat' => Yii::t('package-accounts', 'Введите повтор нового пароля'),
        ];
    }
}
