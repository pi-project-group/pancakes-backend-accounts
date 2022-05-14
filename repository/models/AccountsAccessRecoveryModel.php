<?php
namespace pancakes\accounts\repository\models;

use pancakes\kernel\base\FormModel;

class AccountsAccessRecoveryModel extends FormModel
{
    public $email;
    public $verify_code;
}
