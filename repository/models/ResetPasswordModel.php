<?php
namespace pancakes\accounts\repository\models;

use pancakes\kernel\base\FormModel;

class ResetPasswordModel extends FormModel
{
    public $password;
    public $password_confirm;
}
