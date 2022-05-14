<?php

namespace pancakes\accounts\modules\suspicious_activity\repository\responseModels;

use pancakes\accounts\modules\suspicious_activity\repository\ar\AccountsSuspiciousActivityAR;
use pancakes\accounts\repository\responseModels\AccountsShortModel;
use Yii;

class AccountsSuspiciousActivityModel extends AccountsSuspiciousActivityAR
{
    /**
    * @inheritdoc
    */
    public function fields()
    {
        $fields['id'] = function(){
            return $this->id;
        };
        $fields['type_id'] = function(){
            return $this->type_id;
        };
        $fields['account'] = function(){
            return $this->account;
        };
        $fields['is_auto_ban'] = function(){
            return $this->is_auto_ban;
        };
        $fields['count'] = function(){
            return $this->count;
        };
        $fields['status'] = function(){
            return $this->status;
        };
        $fields['checked_account'] = function(){
            return $this->checkedAccount;
        };
        $fields['checked_at'] = function(){
            return Yii::$app->getFormatter()->asDatetime($this->checked_at);
        };
        $fields['comment_internal'] = function(){
            return $this->comment_internal;
        };
        $fields['created_at'] = function(){
            return Yii::$app->getFormatter()->asDatetime($this->created_at);
        };
        return $fields;
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return parent::attributeLabels();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(AccountsShortModel::class, ['id' => 'account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheckedAccount()
    {
        return $this->hasOne(AccountsShortModel::class, ['id' => 'checked_account_id'])
            ->from(AccountsShortModel::tableName() . ' checked_account');
    }
}
