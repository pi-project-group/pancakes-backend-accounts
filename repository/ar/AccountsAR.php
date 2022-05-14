<?php

namespace pancakes\accounts\repository\ar;

use pancakes\filestorage\repository\ar\FilestorageObjectsAR;
use pancakes\filestorage\repository\responseModels\FilestorageObjectsModel;
use pancakes\kernel\base\ActiveRecord;
use pancakes\kernel\base\utils\OtherUtils;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Connection;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%accounts}}".
 *
 * @property int $id
 * @property string $public_key
 * @property string $username
 * @property int $avatar_obj_id
 * @property string $auth_token
 * @property string $password_hash
 * @property string $email
 * @property string $new_email
 * @property string $status_of_email_confirm
 * @property string $email_confirm_token
 * @property string $email_confirm_code
 * @property string $email_confirm_generated_keys_at
 * @property int $auth_secure_type
 * @property string $auth_secure_code
 * @property int $failed_auth_counter
 * @property int $status
 * @property int $role
 * @property string $created_at
 * @property string $updated_at
 * @property string $last_activity
 *
 * @property FilestorageObjectsModel $avatar
 * @property FilestorageObjectsModel[] $avatars
 * @property AccountsProfileAR $profile
 */
class AccountsAR extends ActiveRecord
{
    const SERVER_USER = 1;

    const STATUS_ACTIVE = 1;
    const STATUS_NOT_ACTIVE = 2;
    const STATUS_BANED = 3;

    const EMAIL_FIRST_NOT_CONFIRM = 0;
    const EMAIL_CONFIRM_TRUE = 1;
    const EMAIL_CHANGE_NOT_CONFIRM = 2;
    const EMAIL_NEW_NOT_CONFIRM = 3;

    // Тип защищенной авторизации
    const AUTH_SECURE_TYPE_DISABLED = 0;
    const AUTH_SECURE_TYPE_BY_IP = 1;
    const AUTH_SECURE_TYPE_ENABLED = 2;

    // App roles
    const ROLE_USER = 1;
    const ROLE_PRIVILEGED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%accounts}}';
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
            'public_key' => Yii::t('package-accounts', 'Публичный ключ'),
            'username' => Yii::t('package-accounts', 'Логин'),
            'avatar_obj_id' => Yii::t('package-accounts', 'Avatar Obj Id'),
            'auth_token' => Yii::t('package-accounts', 'Auth Token'),
            'password_hash' => Yii::t('package-accounts', 'Password Hash'),
            'email' => Yii::t('package-accounts', 'Email'),
            'status_of_email_confirm' => Yii::t('package-accounts', 'Почта подтверждена'),
            'auth_secure_type' => Yii::t('package-accounts', 'Тип безопасной авторизации'),
            'auth_secure_code' => Yii::t('package-accounts', 'Код безопасной авторизации'),
            'failed_auth_counter' => Yii::t('package-accounts', 'Кол-во не удачных авторизаций'),
            'status' => Yii::t('package-accounts', 'Статус'),
            'created_at' => Yii::t('package-accounts', 'Создан'),
            'updated_at' => Yii::t('package-accounts', 'Обновлен'),
            'last_activity' => Yii::t('package-accounts', 'Активность'),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            return true;
        } else {
            if($this->id == self::SERVER_USER) {
                throw new \Exception(Yii::t('package-accounts', 'Запрещенно редактировать этого пользователя.'));
            }
            return false;
        }
    }

    /**
     * @param $id
     * @param $email
     * @param $publicKey
     * @return ActiveQuery
     */
    public static function queryFindUser($id = null, $email = null, $publicKey = null){
        return self::find()->andFilterWhere([
            'id' => $id,
            'email' => $email,
            'public_key' => $publicKey
        ]);
    }

    /**
     * @param $login
     * @return ActiveQuery
     */
    public static function queryFindAccountForLogin($login){
        return self::find()->andWhere(['OR',
            ['username' => $login],
            ['email' => $login]
        ]);
    }

    /**
     * Поиск аккаунта по публичному ключу
     * @param $publicKey
     * @return AccountsAR
     */
    public static function findAccountByPublicKey($publicKey){
        /** @var $result AccountsAR */
        $result = self::find()->andWhere(['public_key' => $publicKey])->one();
        return $result;
    }

    /**
     * Генерация username по переданному email. Постфиксирует если полученный логин уже существует
     * @param $email
     * @return string
     */
    public static function generateUserNameByEmail($email){
        $username = explode("@", $email)[0];
        if(self::find()->where(['username' => $username])->exists()){
            $username .= '_' . substr(md5($email), 0, 5);
        }
        return $username;
    }

    /**
     * Генерация ключей для подтверждения эл. почты.
     * @param $code
     * @throws \Exception
     */
    public function generateEmailConfirmTokens($code = null){
        $time = time();
        $this->email_confirm_token = OtherUtils::generateToken() . '_' . $time;
        if(!empty($code)){
            $code_hash = password_hash($code, PASSWORD_BCRYPT, ['cost' => 5]);
            $this->email_confirm_code = $code_hash . '|' . $time . '|' . 0;
        }
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->hasOne(FilestorageObjectsAR::class, ['id' => 'avatar_obj_id']);
    }

    /**
     * @return string
     */
    public function getAvatars()
    {
        return $this->hasMany(FilestorageObjectsAR::class, ['id' => 'avatar_obj_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(AccountsProfileAR::class, ['account_id' => 'id']);
    }

    /**
     * Возвращает статусы юзера
     * @param bool $type
     * @return array|mixed
     */
    public static function getAuthSecureTypes($type = false){
        $types = [
            self::AUTH_SECURE_TYPE_DISABLED => Yii::t('package-accounts', 'Без подтверждения входа'),
            self::AUTH_SECURE_TYPE_BY_IP => Yii::t('package-accounts', 'Подтверждать вход из подозрительных мест чз почту'),
            self::AUTH_SECURE_TYPE_ENABLED => Yii::t('package-accounts', 'Всегда подтверждать вход чз почту'),
        ];
        return $type !== false ? ArrayHelper::getValue($types, $type) : $types;
    }

    /**
     * Возвращает статусы юзера
     * @param bool $type
     * @return array|mixed
     * @throws \Exception
     */
    public static function getStatuses(bool $type = false){
        $types = [
            self::STATUS_ACTIVE => Yii::t('package-accounts', 'Активный'),
            self::STATUS_NOT_ACTIVE => Yii::t('package-accounts', 'Не активный'),
            self::STATUS_BANED => Yii::t('package-accounts', 'Забанен'),
        ];
        return $type !== false ? ArrayHelper::getValue($types, $type) : $types;
    }

    /**
     * Возвращает состояния подтверждения email
     * @param bool $type
     * @return array|mixed
     * @throws \Exception
     */
    public static function getEmailConfirmStates(bool $type = false){
        $types = [
            self::EMAIL_FIRST_NOT_CONFIRM => Yii::t('package-accounts', 'Регистрационный Email не подтвержден'),
            self::EMAIL_CONFIRM_TRUE => Yii::t('package-accounts', 'Email подтвержден'),
            self::EMAIL_CHANGE_NOT_CONFIRM => Yii::t('package-accounts', 'Изменение Email не подтверждено'),
            self::EMAIL_NEW_NOT_CONFIRM => Yii::t('package-accounts', 'Новый Email не подтвержден'),
        ];
        return $type !== false ? ArrayHelper::getValue($types, $type) : $types;
    }

    /**
     * @param bool $type
     * @return array|mixed
     * @throws \Exception
     */
    public static function getRoles(bool $type = false){
        $types = [
            self::ROLE_USER => Yii::t('package-accounts', 'Обычный'),
            self::ROLE_PRIVILEGED => Yii::t('package-accounts', 'Привилегированный'),
        ];
        return $type !== false ? ArrayHelper::getValue($types, $type) : $types;
    }
}
