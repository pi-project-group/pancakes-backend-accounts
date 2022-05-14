<?php
namespace pancakes\accounts\controllers\validators;

use pancakes\accounts\repository\models\ChangeEmailModel;
use pancakes\accounts\services\AuthenticationService;
use Yii;

class ChangeEmailForm extends ChangeEmailModel
{
    public $new_email;
    public $new_email_repeat;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['new_email', 'new_email_repeat'], 'required'],
            [['new_email', 'new_email_repeat'], 'email'],
            ['new_email_repeat', 'compare', 'compareAttribute'=>'new_email'],
            ['new_email',  function($attribute) {
                $service = new AuthenticationService();
                if($service->isAccountEmailExists($this->new_email)){
                    $this->addError($attribute, Yii::t('package-accounts', 'Тайкой Email уже существует'));
                }
            }]

        ];
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'new_email' => Yii::t('package-accounts', 'Новый Email'),
            'new_email_repeat' => Yii::t('package-accounts', 'Повтор нового Email'),
        ];
    }
}
