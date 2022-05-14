<?php

namespace pancakes\accounts\repository\ar;

use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\Connection;

/**
 * This is the model class for table "{{%accounts_change_email}}".
 *
 * @property int $id
 * @property int $account_id
 * @property string $prev_email Предыдущий email
 * @property string $new_email Новый email
 * @property int $author_id
 * @property string $created_at
 *
 * @property AccountsAR $account
 * @property AccountsAR $author
 */
class AccountsEmailsLogAR extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%accounts_email_log}}';
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
            'account_id' => Yii::t('package-accounts', 'Аккаунт'),
            'prev_email' => Yii::t('package-accounts', 'Предыдущий'),
            'new_email' => Yii::t('package-accounts', 'Новый'),
            'author_id' => Yii::t('package-accounts', 'Автор'),
            'created_at' => Yii::t('package-accounts', 'Создан'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(AccountsAR::class, ['id' => 'account_id'])
            ->from(['account' => AccountsAR::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(AccountsAR::class, ['id' => 'author_id'])
            ->from(['author' => AccountsAR::tableName()]);
    }
}
