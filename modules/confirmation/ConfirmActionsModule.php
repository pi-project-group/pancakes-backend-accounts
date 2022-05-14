<?php

namespace pancakes\accounts\modules\confirmation;

use pancakes\kernel\base\rest\RestModule;

/**
 * cors-origin module definition class
 */
class ConfirmActionsModule extends RestModule
{
    public $desc = 'Подтверждение действий';
    public $enableRbac = true;

    public $restControllers = [
    ];

    public $consoleControllers = [
    ];

    public function reconfigurationHandler() {
    }
}
