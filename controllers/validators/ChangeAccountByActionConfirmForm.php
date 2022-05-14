<?php
namespace pancakes\accounts\controllers\validators;

use pancakes\accounts\repository\ar\AccountsAR;
use pancakes\accounts\services\AuthenticationService;
use pancakes\accounts\services\ManageUsernameAccountsService;
use pancakes\kernel\base\FormModel;
use Yii;

class ChangeAccountByActionConfirmForm extends FormModel
{
    public $username;
    public $email;

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
        return [
            [['username', 'email'], 'required'],
            [['username'], 'string'],
            [['email'], 'email'],
            ['username',  function($attribute) {
                if(!empty($this->account)) {
                    $service = new ManageUsernameAccountsService();
                    if($service->isAccountExists($this->username) && $this->username != $this->account->username){
                        $this->addError($attribute, Yii::t('package-accounts', 'Тайкой логин уже занят'));
                    }
                }
            }],
            ['email',  function($attribute) {
                if(!empty($this->account)) {
                    $service = new AuthenticationService();
                    if($service->isAccountEmailExists($this->email) && $this->email != $this->account->email){
                        $this->addError($attribute, Yii::t('package-accounts', 'Тайкой email уже занят'));
                    }
                }
            }]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('package-accounts', 'Имя пользователя'),
            'email' => Yii::t('package-accounts', 'Email')
        ];
    }
}
