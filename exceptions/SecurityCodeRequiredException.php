<?php
namespace pancakes\accounts\exceptions;

use Exception;
use Yii;

class SecurityCodeRequiredException extends Exception
{
    public function __construct($message = "")
    {
        $message = empty($message)
            ? Yii::t('package-accounts', 'Требуется защитный код для авторизации')
            : $message;
        parent::__construct($message);
    }
}
