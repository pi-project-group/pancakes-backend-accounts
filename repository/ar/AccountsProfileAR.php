<?php

namespace pancakes\accounts\repository\ar;

use pancakes\filestorage\utils\FileManagerHelper;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Connection;

/**
 * This is the model class for table "{{%accounts_profile}}".
 *
 * @property int $account_id
 * @property string $full_name
 * @property string $birth_dt
 * @property string $time_zone
 * @property string $utc
 * @property int $gender
 *
 * @property AccountsAR $user
 */
class AccountsProfileAR extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%accounts_profile}}';
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
            'account_id' => Yii::t('package-accounts', 'Account Id'),
            'full_name' => Yii::t('package-accounts', 'Full Name'),
            'birth_dt' => Yii::t('package-accounts', 'Birth Dt'),
            'time_zone' => Yii::t('package-accounts', 'Time Zone'),
            'utc' => Yii::t('package-accounts', 'Utc'),
            'gender' => Yii::t('package-accounts', 'Gender'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(AccountsAR::class, ['id' => 'account_id']);
    }
}
