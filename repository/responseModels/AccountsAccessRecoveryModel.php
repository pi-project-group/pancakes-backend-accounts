<?php

namespace pancakes\accounts\repository\responseModels;

use pancakes\accounts\repository\ar\AccountsAccessRecoveryAR;

class AccountsAccessRecoveryModel extends AccountsAccessRecoveryAR
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
