<?php

namespace pancakes\accounts\repository\ar;

use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\Connection;
use yii\db\Expression;

/**
 * This is the model class for table "{{%accounts_auth_log}}".
 *
 * @property int $id
 * @property int $account_id
 * @property string $user_agent
 * @property string $device
 * @property string $os
 * @property string $browser
 * @property int $inet_ip
 * @property string $referer_url
 * @property string $created_at
 *
 * @property AccountsAR $account
 */
class AccountsAuthLogAR extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%accounts_auth_log}}';
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
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
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
            'account_id' => Yii::t('package-accounts', 'Аккаунт'),
            'user_agent' => Yii::t('package-accounts', 'User Agent'),
            'device' => Yii::t('package-accounts', 'Устройство'),
            'os' => Yii::t('package-accounts', 'ОС'),
            'browser' => Yii::t('package-accounts', 'Браузер'),
            'inet_ip' => Yii::t('package-accounts', 'Ip'),
            'referer_url' => Yii::t('package-accounts', 'Referer Url'),
            'created_at' => Yii::t('package-accounts', 'Создан'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(AccountsAR::class, ['id' => 'account_id']);
    }
}
