<?php

namespace pancakes\accounts\repository\responseModels;

use pancakes\accounts\base\AccountIdentity;

class AuthenticationModel extends AccountIdentity
{
    /**
    * @inheritdoc
    */
    public function fields()
    {
        $fields = [];
        $fields['auth_token'] = function(){
            return $this->auth_token;
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
