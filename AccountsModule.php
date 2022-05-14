<?php

namespace pancakes\accounts;

use pancakes\accounts\controllers\api\email\EmailController;
use pancakes\accounts\modules\confirmation\ConfirmActionsModule;
use pancakes\accounts\modules\suspicious_activity\SuspiciousActivityModule;
use pancakes\kernel\base\rest\RestModule;
use pancakes\accounts\controllers\api\account\AccountController;
use pancakes\accounts\controllers\api\account_manage\AccountsManageController;
use pancakes\accounts\controllers\api\auth_log\AuthLogController;

/**
 * kernel module definition class
 */
class AccountsModule extends RestModule
{
    public $desc = 'Учетные записи';
    public $enableRbac = true;

    public $restControllers = [
        'account' => AccountController::class,
        'account-manage' => AccountsManageController::class,
        'auth-log' => AuthLogController::class,
        'email' => EmailController::class,
    ];

    public $childrenModules = [
        'suspicious-activity' => SuspiciousActivityModule::class,
        'confirm-actions' => ConfirmActionsModule::class,
    ];
}
