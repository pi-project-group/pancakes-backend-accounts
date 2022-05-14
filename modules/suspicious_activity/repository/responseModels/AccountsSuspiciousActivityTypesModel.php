<?php

namespace pancakes\accounts\modules\suspicious_activity\repository\responseModels;

use pancakes\accounts\modules\suspicious_activity\repository\ar\AccountsSuspiciousActivityTypesAR;

class AccountsSuspiciousActivityTypesModel extends AccountsSuspiciousActivityTypesAR
{
    /**
    * @inheritdoc
    */
    public function fields()
    {
        $fields['id'] = function(){
            return $this->id;
        };
        $fields['name'] = function(){
            return $this->name;
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
}
