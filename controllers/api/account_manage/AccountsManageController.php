<?php
namespace pancakes\accounts\controllers\api\account_manage;

use pancakes\accounts\controllers\api\account_manage\actions\AvatarRemoveAction;
use pancakes\accounts\controllers\api\account_manage\actions\ChangeAvatarAction;
use pancakes\accounts\controllers\api\account_manage\actions\ChangeStatusAction;
use pancakes\accounts\controllers\api\account_manage\actions\ChangeUsernameAction;
use pancakes\accounts\controllers\api\account_manage\actions\CreateAccountAction;
use pancakes\accounts\controllers\api\account_manage\actions\FastSearchAccountAction;
use pancakes\accounts\controllers\api\account_manage\actions\ForcedChangeAccountEmailAction;
use pancakes\accounts\controllers\api\account_manage\actions\GetAccountEmailsAction;
use pancakes\accounts\controllers\api\account_manage\actions\GetAccountStatusLogAction;
use pancakes\accounts\controllers\api\account_manage\actions\GetAccountUserNamesLogAction;
use pancakes\accounts\controllers\api\account_manage\actions\IndexAction;
use pancakes\accounts\controllers\api\account_manage\actions\ViewAction;
use pancakes\kernel\base\rest\OptionsRestBaseAction;
use pancakes\kernel\base\rest\RestController;

/**
 * Class AccountManageController
 * @package rest\modules\locale\controllers\api\account_manage
 */
class AccountsManageController extends RestController
{
    public $desc = 'Управление уч. записями';
    public $enableRbac = true;

    public $customControllerRule = 'v1/accounts/accounts-manage';
    public $urlRuleTokens = [
        '{id}' => '<id:\\w+>',
        '{publicKey}' => '<publicKey:\\w+>',
    ];

    public $guestActions = ['options'];
    public $authActions = [];

    public function actions()
    {
        return [
            'create' => [
                'class' => CreateAccountAction::class,
                'desc' => 'Создать учетную запись',
                'methods' => ['POST'],
                'urlPatterns' => ['']
            ],
            'get-all' => [
                'class' => IndexAction::class,
                'desc' => 'Просмотр списка всех учетных записей',
                'methods' => ['GET'],
                'urlPatterns' => ['']
            ],
            'get-one' => [
                'class' => ViewAction::class,
                'desc' => 'Просмотр учетной записи',
                'methods' => ['GET'],
                'urlPatterns' => ['{publicKey}']
            ],
            'get-account-status-log' => [
                'class' => GetAccountStatusLogAction::class,
                'desc' => 'Получение лога статусов пользователя',
                'methods' => ['GET'],
                'urlPatterns' => ['{publicKey}/get-account-status-log']
            ],
            'get-username-log' => [
                'class' => GetAccountUserNamesLogAction::class,
                'desc' => 'Получение лога изменений логина пользователя',
                'methods' => ['GET'],
                'urlPatterns' => ['{publicKey}/get-username-log']
            ],
            'get-account-emails' => [
                'class' => GetAccountEmailsAction::class,
                'desc' => 'Получение лога email адресов пользователя',
                'methods' => ['GET'],
                'urlPatterns' => ['{publicKey}/get-account-emails']
            ],
            'change-status' => [
                'class' => ChangeStatusAction::class,
                'desc' => 'Изменение статуса',
                'methods' => ['POST'],
                'urlPatterns' => ['{publicKey}/change-status']
            ],
            'change-username' => [
                'class' => ChangeUsernameAction::class,
                'desc' => 'Изменение логина пользователю',
                'methods' => ['POST', 'PUT'],
                'urlPatterns' => ['{publicKey}/change-username']
            ],
            'forced-change-email' => [
                'class' => ForcedChangeAccountEmailAction::class,
                'desc' => 'Принудительное изменение email уч. записи',
                'methods' => ['POST'],
                'urlPatterns' => ['{publicKey}/forced-change-email']
            ],
            'change-avatar' => [
                'class' => ChangeAvatarAction::class,
                'desc' => 'Изменение изображения пользователя',
                'methods' => ['POST'],
                'urlPatterns' => ['{publicKey}/change-avatar']
            ],
            'remove-avatar' => [
                'class' => AvatarRemoveAction::class,
                'desc' => 'Удаление изображения пользователя',
                'methods' => ['POST'],
                'urlPatterns' => ['{publicKey}/remove-avatar']
            ],
            'fast-search-account' => [
                'class' => FastSearchAccountAction::class,
                'desc' => 'Быстрый поиск аккаунта',
                'methods' => ['GET'],
                'urlPatterns' => ['fast-search-account']
            ],
            'options' => [
                'class' => OptionsRestBaseAction::class,
            ],
        ];
    }
}
