<?php
namespace pancakes\accounts\controllers\validators;

use pancakes\accounts\repository\ar\AccountsAR;
use pancakes\kernel\base\FormModel;
use Yii;

class ChangePasswordForm extends FormModel
{
    public $current_password;
    public $new_password;
    public $new_password_repeat;

    public $account;

    public function __construct(AccountsAR $account = null)
    {
        parent::__construct();
        $this->account = $account;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['current_password', 'new_password', 'new_password_repeat'], 'required'],
            ['current_password', 'string'],
            [['new_password'], 'string', 'min' => 6],
            ['new_password_repeat', 'compare', 'compareAttribute'=>'new_password'],
            ['current_password', function ($attribute, $params, $validator) {
                if(!password_verify($this->current_password, $this->account->password_hash)){
                    $this->addError($attribute, 'Текущий пароль не верен');
                } elseif ($this->current_password == $this->new_password) {
                    $this->addError($attribute, 'Новый пароль должен отличаться от текущего');
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
            'current_password' => Yii::t('package-accounts', 'Текущий пароль'),
            'new_password' => Yii::t('package-accounts', 'Новый пароль'),
            'new_password_repeat' => Yii::t('package-accounts', 'Повтор нового пароля'),
        ];
    }
}
