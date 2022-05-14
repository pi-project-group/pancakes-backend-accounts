<?php

namespace pancakes\accounts;

use pancakes\accounts\repository\ar\AccountsAR;
use pancakes\kernel\base\ReferenceData;
use pancakes\kernel\base\ReferenceDataModel;

class AccountReferenceData extends ReferenceData
{
    /**
     * @throws \Exception
     */
    public static function getAccountStatuses() {
        $items = AccountsAR::getStatuses();
        asort($items);
        $items = array_map(function($key, $value) {
            return [
                'value' => $key,
                'title' => $value
            ];
        }, array_keys($items), $items);
        return new ReferenceDataModel(
            \Yii::t('package-accounts', 'Состояния аккаунтов'),
            $items
        );
    }

    /**
     * @throws \Exception
     */
    public static function getAccountRoles() {
        $items = AccountsAR::getRoles();
        asort($items);
        $items = array_map(function($key, $value) {
            return [
                'value' => $key,
                'title' => $value
            ];
        }, array_keys($items), $items);
        return new ReferenceDataModel(
            \Yii::t('package-accounts', 'Роли пользователей'),
            $items
        );
    }
}
