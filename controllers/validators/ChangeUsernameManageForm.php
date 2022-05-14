<?php
namespace pancakes\accounts\controllers\validators;

use pancakes\accounts\repository\models\ChangeUsernameModel;
use pancakes\accounts\services\ManageUsernameAccountsService;
use Yii;

class ChangeUsernameManageForm extends ChangeUsernameModel
{
    public $newUsername;
    public $comment;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['newUsername', 'comment'], 'required'],
            [['newUsername', 'comment'], 'string'],
            ['newUsername',  function($attribute) {
                $service = new ManageUsernameAccountsService();
                if($service->isAccountExists($this->newUsername)){
                    $this->addError($attribute, Yii::t('package-accounts', 'Тайкой логин уже занят'));
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
            'newUsername' => Yii::t('package-accounts', 'Новый логин'),
            'comment' => Yii::t('package-accounts', 'Комментарий'),
        ];
    }
}
