<?php
namespace pancakes\accounts\repository\models;

use pancakes\kernel\base\FormModel;

class ChangeEmailModel extends FormModel
{
    public $new_email;
    public $new_email_repeat;
}
