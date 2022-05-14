<?php

namespace pancakes\accounts\modules\suspicious_activity;

use pancakes\accounts\modules\suspicious_activity\controllers\api\main\MainController;
use pancakes\kernel\base\rest\RestModule;

/**
 * Предназначен для учета подозрительной активности пользователей приложения.
 * Нужно оценить какую либо активность в каком либо функционале и добавить записть в список подозрительных активностей
 * после чего администратор должен проинспектировать эту активность и вынести решение о ней (бан или все ок).
 * suspicious-activity module definition class
 */
class SuspiciousActivityModule extends RestModule
{
    public $desc = 'Учет подозрительной активности';
    public $enableRbac = true;

    public $restControllers = [
        'main' => MainController::class
    ];

    public $consoleControllers = [
    ];
}
