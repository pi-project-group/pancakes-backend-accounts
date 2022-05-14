<?php
namespace pancakes\accounts\repository\models;

use pancakes\kernel\base\FormModel;

class ChangeUsernameModel extends FormModel
{
    public $newUsername;
    public $comment;
}
