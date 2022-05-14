<?php

namespace pancakes\accounts\services;

use pancakes\accounts\controllers\validators\ChangeAccountByActionConfirmForm;
use pancakes\accounts\repository\ar\AccountsAR;
use pancakes\accounts\modules\confirmation\services\ConfirmActionsService;

/**
 * Сервис для редактирования профиля
 * @property AccountsAR $dataGateway
 */
class AccountEditByActionConfirmService
{
    protected $dataGateway;

    public function __construct(AccountsAR $dataGateway = null)
    {
        $this->dataGateway = !empty($dataGateway) ? $dataGateway : new AccountsAR();
    }

    /**
     * @param AccountsAR $account
     * @param ChangeAccountByActionConfirmForm $form
     * @return \pancakes\accounts\modules\confirmation\repository\ar\ConfirmActionsAR
     * @throws \Exception
     */
    public function createEditRequest(AccountsAR $account, ChangeAccountByActionConfirmForm $form) {
        $service = new ConfirmActionsService();
        return $service->create($form->loadParams, $account->id);
    }
}
