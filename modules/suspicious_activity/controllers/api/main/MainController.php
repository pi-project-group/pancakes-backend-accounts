<?php

namespace pancakes\accounts\modules\suspicious_activity\controllers\api\main;

use pancakes\accounts\modules\suspicious_activity\controllers\api\main\actions\AllAction;
use pancakes\accounts\modules\suspicious_activity\controllers\api\main\actions\InspectAction;
use pancakes\kernel\base\rest\OptionsRestBaseAction;
use pancakes\kernel\base\rest\RestController;

/**
 * Main controller for the `accounts/suspicious_activity` module
 */
class MainController extends RestController
{
    public $desc = 'Основные действия';
    public $enableRbac = true;

    public $customControllerRule = 'v1/kernel/suspicious-activity';
    public $urlRuleTokens = ['{id}' => '<id:\\d+>'];

    public $guestActions = ['options'];
    public $authActions = [];

    public function actions()
    {
        return [
            'all' => [
                'class' => AllAction::class,
                'desc' => 'Просмотр списка всех активностей',
                'methods' => ['GET'],
                'urlPatterns' => ['']
            ],
            'inspect' => [
                'class' => InspectAction::class,
                'desc' => 'Инспекция активности',
                'methods' => ['POST'],
                'urlPatterns' => ['inspect/{id}']
            ],
            'options' => [
                'class' => OptionsRestBaseAction::class,
            ],
        ];
    }
}
