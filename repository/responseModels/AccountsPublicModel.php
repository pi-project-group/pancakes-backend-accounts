<?php

namespace pancakes\accounts\repository\responseModels;

use pancakes\accounts\repository\ar\AccountsAR;

class AccountsPublicModel extends AccountsAR
{
    /**
    * @inheritdoc
    */
    public function fields()
    {
        $fields['public_key'] = function(){
            return $this->public_key;
        };
        $fields['username'] = function(){
            return $this->username;
        };
        $fields['email'] = function(){
            return $this->email;
        };
        $fields['status_of_email_confirm'] = function(){
            return $this->status_of_email_confirm;
        };
        $fields['status'] = function(){
            return $this->status;
        };
        $fields['avatar_url'] = function(){
            if (!empty($this->avatar)) {
                return $this->avatar->getUrl();
            }
            return null;
        };
        $fields['created_at'] = function(){
            return $this->created_at;
        };
        $fields['updated_at'] = function(){
            return $this->updated_at;
        };
        return $fields;
    }

    /**
    * @inheritdoc
    */
    public function extraFields()
    {
        return [];
    }
}
