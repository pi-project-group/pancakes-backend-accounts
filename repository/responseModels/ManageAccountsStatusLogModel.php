<?php

namespace pancakes\accounts\repository\responseModels;

use pancakes\accounts\repository\ar\AccountsStatusLogAR;
use Yii;

class ManageAccountsStatusLogModel extends AccountsStatusLogAR
{
    /**
    * @inheritdoc
    */
    public function fields()
    {
        $fields['id'] = function(){
            return $this->id;
        };
        $fields['status'] = function(){
            return $this->status;
        };
        $fields['comment'] = function(){
            return $this->comment;
        };
        $fields['accountPublicKey'] = function(){
            return $this->account->public_key;
        };
        $fields['accountUsername'] = function(){
            return $this->account->username;
        };
        $fields['authorPublicKey'] = function(){
            return $this->author->public_key;
        };
        $fields['authorUsername'] = function(){
            return $this->author->username;
        };
        $fields['created_at'] = function(){
            return $this->created_at;
        };
        return $fields;
    }

    public function attributeLabels()
    {
        $attributes = parent::attributeLabels();
        $attributes['accountPublicKey'] = Yii::t('package-accounts', 'Публичный ключ аккаунта');
        $attributes['accountUsername'] = Yii::t('package-accounts', 'Аккаунт');
        $attributes['authorPublicKey'] = Yii::t('package-accounts', 'Публичный ключ автора');
        $attributes['authorUsername'] = Yii::t('package-accounts', 'Автор');
        return $attributes;
    }

    /**
    * @inheritdoc
    */
    public function extraFields()
    {
        return [];
    }
}
