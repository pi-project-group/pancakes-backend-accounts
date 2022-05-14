<?php
namespace pancakes\accounts\modules\suspicious_activity\controllers\validators;

use pancakes\accounts\repository\ar\AccountsAR;
use pancakes\kernel\base\FormModel;
use Yii;

class AccountsSuspiciousActivityInspectFrom extends FormModel
{
    public $comment_internal;
    public $account_status;

    /**
     * @inheritdoc
     */
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_internal'], 'string'],
            [['account_status'], 'integer'],
            [['account_status'], 'in', 'range' => [AccountsAR::STATUS_ACTIVE, AccountsAR::STATUS_BANED]]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'comment_internal' => Yii::t('package-accounts', 'Внутренний комментарий'),
            'account_status' => Yii::t('package-accounts', 'Состояние аккаунта')
        ];
    }
}
