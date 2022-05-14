<?php

namespace pancakes\accounts\modules\suspicious_activity\repository\ar;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\Connection;

/**
 * This is the model class for table "{{%accounts_suspicious_activity_types}}".
 *
 * @property int $id
 * @property string $name Название типа подозрительной активности пользователя
 * @property boolean $activity_threshold Порог активности
 * @property boolean $is_auto_ban Автобан
 *
 * @property AccountsSuspiciousActivityAR[] $accountsSuspiciousActivities
 */
class AccountsSuspiciousActivityTypesAR extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%accounts_suspicious_activity_types}}';
    }

    /**
     * @return Connection the database connection used by this AR class.
     * @throws InvalidConfigException
     */
    public static function getDb()
    {
        /** @var $connection Connection */
        $connection = Yii::$app->get('db');
        return $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('package-accounts', 'ID'),
            'name' => Yii::t('package-accounts', 'Название типа подозрительной активности пользователя'),
            'activity_threshold' => Yii::t('package-accounts', 'Порог активности'),
            'is_auto_ban' => Yii::t('package-accounts', 'Автобан'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountsSuspiciousActivities()
    {
        return $this->hasMany(AccountsSuspiciousActivityAR::class, ['type_id' => 'id']);
    }
}
