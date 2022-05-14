<?php

namespace pancakes\accounts\repository\ar;

use pancakes\accounts\base\AccountIdentity;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\Expression;

/**
 * This is the model class for table "{{%accounts_access_recovery}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $token
 * @property int $status
 * @property string $created_at
 */
class AccountsAccessRecoveryAR extends ActiveRecord
{
    const STATUS_NOT_USED = 0;
    const STATUS_USED = 1;
    const STATUS_OUTDATED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%accounts_access_recovery}}';
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
            'user_id' => Yii::t('package-accounts', 'User ID'),
            'token' => Yii::t('package-accounts', 'Token'),
            'status' => Yii::t('package-accounts', 'Status'),
            'created_at' => Yii::t('package-accounts', 'Created At'),
        ];
    }

    /**
     * Получение аккаунта по действительному токену
     * @param $token
     * @return array|ActiveRecord|null
     */
    public static function getAccountByToken($token){
        $tokenExpireMin = env('USER_PASSWORD_RESET_TOKEN_EXPIRE') / 60;
        /** @var $tokenModel self */
        $tokenModel = self::find()
            ->where(['token' => $token, 'status' => self::STATUS_NOT_USED])
            ->andWhere("created_at >= now() - INTERVAL {$tokenExpireMin} MINUTE")
            ->one();
        if (!empty($tokenModel)) {
            return AccountIdentity::queryFindUser($tokenModel->user_id)->one();
        }
        return null;
    }

    /**
     * Устанавливает статус STATUS_NOT_USED всем не используемым токенам пользователя
     * @param $userId
     * @return int
     */
    public static function setAllOutdatedStatuses($userId){
        return self::updateAll(['status' => self::STATUS_OUTDATED], ['user_id' => $userId, 'status' => self::STATUS_NOT_USED]);
    }

    /**
     * Устанавливает статус STATUS_USED переданному токену
     * @param $token
     * @return int
     */
    public static function setUsedStatus($token){
        return self::updateAll(['status' => self::STATUS_USED], ['token' => $token]);
    }
}
