<?php
namespace pancakes\accounts\exceptions;

use Exception;
use Yii;

class AuthFailException extends Exception
{
    public function __construct($message = "")
    {
        $message = empty($message)
            ? Yii::t('package-accounts', 'Некорректный пароль')
            : $message;
        parent::__construct($message);
    }
}
