<?php

namespace pancakes\accounts\controllers\api\auth_log;

use pancakes\kernel\base\rest\RestController;
use pancakes\accounts\controllers\api\auth_log\actions\GetAllAction;
use pancakes\kernel\base\rest\OptionsRestBaseAction;
use pancakes\accounts\controllers\api\auth_log\actions\GetAllByUserAction;

/**
 * Main controller for the `` module
 */
class AuthLogController extends RestController
{
    public $desc = 'Лог авторизаций пользователей';
    public $enableRbac = true;

    public $customControllerRule = 'v1/accounts/auth-log';
    public $urlRuleTokens = [
        '{id}' => '<id:\\w+>',
        '{publicKey}' => '<publicKey:\\w+>',
    ];

    public $guestActions = ['options'];
    public $authActions = [];

    public function actions()
    {
        return [
            'all' => [
                'class' => GetAllAction::class,
                'desc' => 'Просмотр списка всех авторизаций пользователей',
                'methods' => ['GET'],
                'urlPatterns' => ['']
            ],
            'get-account-auth-log' => [
                'class' => GetAllByUserAction::class,
                'desc' => 'Получение лога авторизаций пользователя',
                'methods' => ['GET'],
                'urlPatterns' => ['{publicKey}/get-account-auth-log']
            ],
            'options' => [
                'class' => OptionsRestBaseAction::class,
            ],
        ];
    }
}
