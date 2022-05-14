<?php
namespace pancakes\accounts\controllers\api\account;

use pancakes\accounts\controllers\api\account\actions\ChangeAuthSecureTypeAction;
use pancakes\accounts\controllers\api\account\actions\ChangeAuthSecureTypeConfirmAction;
use pancakes\accounts\controllers\api\account\actions\ChangePasswordAction;
use pancakes\accounts\controllers\api\account\actions\ChangePasswordByToken;
use pancakes\accounts\controllers\api\account\actions\ChangePasswordConfirmAction;
use pancakes\accounts\controllers\api\account\actions\EditAccountRequestAction;
use pancakes\accounts\controllers\api\account\actions\EditAccountRequestConfirmAction;
use pancakes\accounts\controllers\api\account\actions\RecoveryAccessAction;
use pancakes\accounts\controllers\api\account\actions\ChangeAvatarAction;
use pancakes\accounts\controllers\api\account\actions\GetMyAccountAction;
use pancakes\accounts\controllers\api\account\actions\GetTokenAction;
use pancakes\accounts\controllers\api\account\actions\SignUpAction;
use pancakes\kernel\base\rest\OptionsRestBaseAction;
use pancakes\kernel\base\rest\RestController;

/**
 * Class MainController
 * @package rest\modules\locale\controllers\api\main
 */
class AccountController extends RestController
{
    public $desc = 'Учетная запись';
    public $enableRbac = true;

    public $customControllerRule = 'v1/accounts';
    public $urlRuleTokens = [
        '{id}' => '<id:\\w+>',
        '{token}' => '<token:\\w+>',
    ];

    public $guestActions = [
        'options',
        'sign-up',
        'get-token',
        'recovery-access',
        'change-password-by-token',
        'confirm-sing-up-email'
    ];
    public $authActions = [
        'get-my-account',
        'change-avatar',
        'edit-account',
        'edit-account-confirm',
        'change-password',
        'change-password-confirm',
        'change-auth-secure-type',
        'change-auth-secure-type-confirm'
    ];

    public function actions()
    {
        return [
            'sign-up' => [
                'class' => SignUpAction::class,
                'desc' => 'Регистрация нового аккаунта',
                'methods' => ['POST'],
                'urlPatterns' => ['sign-up']
            ],
            'get-token' => [
                'class' => GetTokenAction::class,
                'desc' => 'Получение токена аутентификации',
                'methods' => ['POST'],
                'urlPatterns' => ['get-token']
            ],
            'recovery-access' => [
                'class' => RecoveryAccessAction::class,
                'desc' => 'Восстановление доступа к аккаунту',
                'methods' => ['POST'],
                'urlPatterns' => ['recovery-access']
            ],
            'change-password-by-token' => [
                'class' => ChangePasswordByToken::class,
                'desc' => 'Смена пароля по токену',
                'methods' => ['POST'],
                'urlPatterns' => ['change-password-by-token']
            ],
            'get-my-account' => [
                'class' => GetMyAccountAction::class,
                'desc' => 'Получение аккаунта авторизированного пользователя',
                'methods' => ['GET'],
                'urlPatterns' => ['my-account']
            ],
            'change-avatar' => [
                'class' => ChangeAvatarAction::class,
                'desc' => 'Изменение изображения авторизированного пользователя',
                'methods' => ['POST'],
                'urlPatterns' => ['change-avatar']
            ],
            'edit-account' => [
                'class' => EditAccountRequestAction::class,
                'desc' => 'Запрос на редактирование аккаунта авторизированного пользователя',
                'methods' => ['POST'],
                'urlPatterns' => ['edit-account']
            ],
            'edit-account-confirm' => [
                'class' => EditAccountRequestConfirmAction::class,
                'desc' => 'Подтверждение редактирования аккаунта авторизированного пользователя',
                'methods' => ['POST'],
                'urlPatterns' => ['edit-account-confirm']
            ],
            'change-password' => [
                'class' => ChangePasswordAction::class,
                'desc' => 'Смена пароля авторизированного пользователя',
                'methods' => ['POST'],
                'urlPatterns' => ['change-password']
            ],
            'change-password-confirm' => [
                'class' => ChangePasswordConfirmAction::class,
                'desc' => 'Подтверждение смены пароля авторизированного пользователя',
                'methods' => ['POST'],
                'urlPatterns' => ['change-password-confirm']
            ],
            'change-auth-secure-type' => [
                'class' => ChangeAuthSecureTypeAction::class,
                'desc' => 'Изменение режима авторизации',
                'methods' => ['POST'],
                'urlPatterns' => ['change-auth-secure-type']
            ],
            'change-auth-secure-type-confirm' => [
                'class' => ChangeAuthSecureTypeConfirmAction::class,
                'desc' => 'Подтверждение изменения режима авторизации',
                'methods' => ['POST'],
                'urlPatterns' => ['change-auth-secure-type-confirm']
            ],
            'options' => [
                'class' => OptionsRestBaseAction::class,
            ],
        ];
    }
}
