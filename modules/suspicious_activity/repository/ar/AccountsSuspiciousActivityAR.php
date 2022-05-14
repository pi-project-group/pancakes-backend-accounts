<?php

namespace pancakes\accounts\modules\suspicious_activity\repository\ar;

use pancakes\accounts\repository\ar\AccountsAR;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\Connection;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%accounts_suspicious_activity}}".
 *
 * @property int $id
 * @property int $type_id Тип активности
 * @property int $account_id
 * @property int $is_auto_ban
 * @property string $count Количество
 * @property int $status Состояние активности
 * @property int $checked_account_id Проверено пользователем
 * @property string $comment_internal Внутренний комментрий
 * @property string $checked_at Время проверки
 * @property string $created_at Время создания
 * @property string $updated_at Время обновления
 *
 * @property AccountsAR $account
 * @property AccountsAR $checkedAccount
 * @property AccountsSuspiciousActivityTypesAR $type
 */
class AccountsSuspiciousActivityAR extends \yii\db\ActiveRecord
{

    const STATUS_AWAITING = 1;
    const STATUS_CHECKED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%accounts_suspicious_activity}}';
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
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => gmdate('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('package-accounts', 'ID'),
            'type_id' => Yii::t('package-accounts', 'Тип активности'),
            'account_id' => Yii::t('package-accounts', 'Account ID'),
            'is_auto_ban' => Yii::t('package-accounts', 'Автобан'),
            'count' => Yii::t('package-accounts', 'Количество'),
            'status' => Yii::t('package-accounts', 'Состояние активности'),
            'checked_account_id' => Yii::t('package-accounts', 'Проверено пользователем'),
            'comment_internal' => Yii::t('package-accounts', 'Внутренний комментрий'),
            'checked_at' => Yii::t('package-accounts', 'Время проверки'),
            'created_at' => Yii::t('package-accounts', 'Время создания'),
            'updated_at' => Yii::t('package-accounts', 'Время обновления'),
        ];
    }

    /**
     * Возвращает статусы юзера
     * @param bool $type
     * @return array|mixed
     */
    public static function getStatuses($type = false){
        $types = [
            self::STATUS_AWAITING => Yii::t('package-accounts', 'Ожидает'),
            self::STATUS_CHECKED => Yii::t('package-accounts', 'Проверен')
        ];
        return $type !== false ? ArrayHelper::getValue($types, $type) : $types;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(AccountsAR::class, ['id' => 'account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheckedAccount()
    {
        return $this->hasOne(AccountsAR::class, ['id' => 'checked_account_id'])
            ->from(AccountsAR::tableName() . ' checked_account');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(AccountsSuspiciousActivityTypesAR::class, ['id' => 'type_id']);
    }
}
