<?php

namespace pancakes\accounts\repository\responseModels;

use pancakes\accounts\AccountReferenceData;
use pancakes\accounts\repository\ar\AccountsAR;

class AccountsShortModel extends AccountsAR
{
    /**
    * @inheritdoc
    */
    public function fields()
    {
        $fields['publicKey'] = function(){
            return $this->public_key;
        };
        $fields['username'] = function(){
            return $this->username;
        };
        $fields['status'] = function(){
            return $this->status;
        };
        $fields['avatarUrl'] = function(){
            if (!empty($this->avatar)) {
                return $this->avatar->getUrl();
            }
            return null;
        };
        return $fields;
    }

    public function attributeLabels()
    {
        return [
            'publicKey' => \Yii::t('package-accounts', 'Публичный ключ'),
            'username' => \Yii::t('package-accounts', 'Имя пользователя'),
            'status' => \Yii::t('package-accounts', 'Состояние'),
            'avatarUrl' => \Yii::t('package-accounts', 'Аватарка'),
        ];
    }

    /**
     * @throws \Exception
     */
    public function fieldsReferenceData()
    {
        return [
            'status' => AccountReferenceData::getAccountStatuses()
        ];
    }
}
