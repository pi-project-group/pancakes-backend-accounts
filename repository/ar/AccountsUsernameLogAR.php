<?php

namespace pancakes\accounts\repository\ar;

use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Connection;
use yii\db\Expression;

/**
 * This is the model class for table "{{%accounts_username_log}}".
 *
 * @property int $id
 * @property int $account_id
 * @property string $before_username Предыдущий username
 * @property string $new_username Новый username
 * @property string $comment
 * @property int $author_id
 * @property string $created_at
 *
 * @property AccountsAR $account
 * @property AccountsAR $author
 */
class AccountsUsernameLogAR extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%accounts_username_log}}';
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
            'blameableBehavior' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'author_id',
                'updatedByAttribute' => false,
                'defaultValue' => AccountsAR::SERVER_USER
            ],
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
            'account_id' => Yii::t('package-accounts', 'Account ID'),
            'before_username' => Yii::t('package-accounts', 'Предыдущий логин'),
            'new_username' => Yii::t('package-accounts', 'Новый логин'),
            'comment' => Yii::t('package-accounts', 'Комментарий'),
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
