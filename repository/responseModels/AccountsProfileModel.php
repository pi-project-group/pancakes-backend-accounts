<?php

namespace pancakes\accounts\repository\responseModels;

use pancakes\accounts\repository\ar\AccountsProfileAR;

class AccountsProfileModel extends AccountsProfileAR
{
    /**
    * @inheritdoc
    */
    public function fields()
    {
        $fields = parent::fields();
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
