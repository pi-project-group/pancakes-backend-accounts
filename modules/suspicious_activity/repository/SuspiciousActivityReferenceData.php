<?php

namespace pancakes\accounts\modules\suspicious_activity\repository;

use pancakes\accounts\modules\suspicious_activity\repository\ar\AccountsSuspiciousActivityAR;
use pancakes\accounts\modules\suspicious_activity\repository\ar\AccountsSuspiciousActivityTypesAR;
use pancakes\kernel\base\ReferenceData;
use pancakes\kernel\base\ReferenceDataModel;
use yii\helpers\ArrayHelper;

class SuspiciousActivityReferenceData extends ReferenceData
{
    protected static function getSuspiciousActivityStates(){
        $statuses = AccountsSuspiciousActivityAR::getStatuses();
        asort($statuses);
        $items = array_map(function($key, $value) {
            return [
                'value' => $key,
                'title' => $value
            ];
        }, array_keys($statuses), $statuses);
        return new ReferenceDataModel(
            \Yii::t('package-accounts', 'Состояния подозрительной активности'),
            $items
        );
    }

    protected static function getAllSuspiciousActivityTypes(){
        $items = ArrayHelper::toArray(AccountsSuspiciousActivityTypesAR::find()->orderBy('name')->all(), [
            AccountsSuspiciousActivityTypesAR::class => [
                'value' => 'id',
                'title' => 'name',
            ],
        ]);
        return new ReferenceDataModel(
            \Yii::t('package-accounts', 'Типы подозрительной активности'),
            $items
        );
    }
}