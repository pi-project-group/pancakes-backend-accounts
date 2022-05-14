<?php

namespace pancakes\accounts\repository\responseModels;

use pancakes\accounts\repository\ar\AccountsAuthLogAR;
use Yii;

class ManageAccountsAuthLogModel extends AccountsAuthLogAR
{
    /**
    * @inheritdoc
    */
    public function fields()
    {
        $fields['id'] = function(){
            return $this->id;
        };
        $fields['user_agent'] = function(){
            return $this->user_agent;
        };
        $fields['device'] = function(){
            return $this->device;
        };
        $fields['os'] = function(){
            return $this->os;
        };
        $fields['browser'] = function(){
            return $this->browser;
        };
        $fields['ip'] = function(){
            return inet_ntop($this->inet_ip);
        };
        $fields['referer_url'] = function(){
            return $this->referer_url;
        };
        $fields['accountPublicKey'] = function(){
            return $this->account->public_key;
        };
        $fields['accountUsername'] = function(){
            return $this->account->username;
        };
        $fields['created_at'] = function(){
            return $this->created_at;
        };
        return $fields;
    }

    public function attributeLabels()
    {
        $attributes = parent::attributeLabels();
        $attributes['ip'] = Yii::t('package-accounts', 'IP');
        $attributes['accountPublicKey'] = Yii::t('package-accounts', 'Публичный ключ аккаунта');
        $attributes['accountUsername'] = Yii::t('package-accounts', 'Аккаунт');
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
