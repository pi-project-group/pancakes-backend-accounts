<?php

namespace pancakes\accounts\modules\confirmation\repository\ar;

use pancakes\accounts\repository\ar\AccountsAR;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\Connection;

/**
 * This is the model class for table "{{%kernel_confirm_actions}}".
 *
 * @property int $id
 * @property string $public_key Публичный ключ
 * @property string $secret_key Секретный ключ
 * @property string $data Данные действия
 * @property int $account_id Владелец действия
 * @property int $attempts Попыток
 * @property string $created_at Создан
 * @property string $confirmed_at Активирован
 *
 * @property AccountsAR $account
 */
class ConfirmActionsAR extends \yii\db\ActiveRecord
{
    public $code = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%accounts_confirm_actions}}';
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
            'public_key' => Yii::t('package-accounts', 'Публичный ключ пара'),
            'secret_key' => Yii::t('package-accounts', 'Секретный ключ'),
            'data' => Yii::t('package-accounts', 'Данные действия'),
            'account_id' => Yii::t('package-accounts', 'Владелец действия'),
            'attempts' => Yii::t('package-accounts', 'Попыток'),
            'created_at' => Yii::t('package-accounts', 'Создан'),
            'confirmed_at' => Yii::t('package-accounts', 'Время активации'),
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
