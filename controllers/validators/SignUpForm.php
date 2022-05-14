<?php
namespace pancakes\accounts\controllers\validators;

use pancakes\accounts\services\AuthenticationService;
use pancakes\kernel\base\FormModel;
use Yii;

class SignUpForm extends FormModel
{
    public $login;
    public $password;
    public $password_repeat;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['login', 'password', 'password_repeat'], 'required'],
            ['login', 'email'],
            [['password', 'password_repeat'], 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute'=>'password'],
            ['login',  function($attribute) {
                $service = new AuthenticationService();
                if($service->isAccountEmailExists($this->login)){
                    $this->addError($attribute, Yii::t('package-accounts', 'Тайкой Email уже существует'));
                }
            }],

        ];
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login' => Yii::t('package-accounts', 'Адрес электронной почты'),
            'password' => Yii::t('package-accounts', 'Пароль'),
            'password_repeat' => Yii::t('package-accounts', 'Повтор пароля')
        ];
    }
    
    public function attributePlaceholders()
    {
        return [
            'login' => Yii::t('package-accounts', 'Your email address'),
            'password' => Yii::t('package-accounts', 'Введите пароль'),
            'password_repeat' => Yii::t('package-accounts', 'Введите пароль еще раз')
        ];
    }
}
