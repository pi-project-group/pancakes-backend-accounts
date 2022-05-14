<?php
namespace pancakes\accounts\controllers\api\email;

use pancakes\accounts\controllers\api\email\actions\CancelChangeEmailAction;
use pancakes\accounts\controllers\api\email\actions\ConfirmChangeEmailByTokenAction;
use pancakes\accounts\controllers\api\email\actions\ConfirmEmailByTokenAction;
use pancakes\accounts\controllers\api\email\actions\ResendChangeEmailConfirmKeysAction;
use pancakes\accounts\controllers\api\email\actions\ResendEmailConfirmKeysAction;
use pancakes\kernel\base\rest\OptionsRestBaseAction;
use pancakes\kernel\base\rest\RestController;

/**
 * Class MainController
 * @package rest\modules\locale\controllers\api\main
 */
class EmailController extends RestController
{
    public $desc = 'Управление своим адресом электронной почты';
    public $enableRbac = true;

    public $customControllerRule = 'v1/accounts/email';
    public $urlRuleTokens = [];

    public $guestActions = [
        'options',
        'confirm-change-email-by-token',
        'confirm-email-by-token',
    ];
    public $authActions = [
        'cancel-change-email',
        'send-change-email-confirm-keys',
        'send-email-confirm-keys'
    ];

    public function actions()
    {
        return [
            'cancel-change-email' => [
                'class' => CancelChangeEmailAction::class,
                'desc' => 'Отмена смены email адреса',
                'methods' => ['POST'],
                'urlPatterns' => ['cancel-change-email']
            ],
            'confirm-change-email-by-token' => [
                'class' => ConfirmChangeEmailByTokenAction::class,
                'desc' => 'Подтверждение смены email адреса по токену',
                'methods' => ['POST'],
                'urlPatterns' => ['confirm-change-email-by-token']
            ],
            'confirm-email-by-token' => [
                'class' => ConfirmEmailByTokenAction::class,
                'desc' => 'Подтверждение email адреса по токену',
                'methods' => ['POST'],
                'urlPatterns' => ['confirm-email-by-token']
            ],
            'send-email-confirm-keys' => [
                'class' => ResendEmailConfirmKeysAction::class,
                'desc' => 'Отправить ключи подтверждения email адреса',
                'methods' => ['POST'],
                'urlPatterns' => ['send-email-confirm-keys']
            ],
            'options' => [
                'class' => OptionsRestBaseAction::class,
            ],
        ];
    }
}
