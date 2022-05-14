<?php
namespace pancakes\accounts\repository\models;

use pancakes\kernel\base\FormModel;

class AccountChangeStatusModel extends FormModel
{
    public $status;
    public $comment;
}
