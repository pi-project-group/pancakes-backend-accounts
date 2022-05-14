<?php

namespace pancakes\accounts\events;

use pancakes\accounts\repository\ar\AccountsAR;
use yii\base\Event;

/**
 * Событие создания нового пользователя
 *
 * @property AccountsAR $account
 */
class CreateAccountEvent extends Event
{
    public $account;

    /**
     * AccountCreateEvent constructor.
     * @param AccountsAR $account
     */
    public function __construct(AccountsAR $account)
    {
        parent::__construct();
        $this->account = $account;
    }
}
