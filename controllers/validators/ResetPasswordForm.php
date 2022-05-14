<?php
namespace pancakes\accounts\controllers\validators;

use pancakes\accounts\repository\models\ResetPasswordModel;
use Yii;

class ResetPasswordForm extends ResetPasswordModel
{
    public $password;
    public $password_confirm;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['password', 'password_confirm'], 'required'],
            ['password', 'string', 'min' => 6],
            ['password_confirm', 'compare', 'compareAttribute'=>'password']

        ];
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('package-accounts', 'Пароль'),
            'password_confirm' => Yii::t('package-accounts', 'Подтверждение пароля')
        ];
    }
}
