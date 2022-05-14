<?php

namespace pancakes\accounts\base;

use Carbon\Carbon;
use pancakes\accounts\repository\ar\AccountsAR;
use Yii;
use yii\web\IdentityInterface;

class AccountIdentity extends AccountsAR implements IdentityInterface
{
    public static function findIdentity($id) {
        return self::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null){
        $user = static::findOne(['auth_token' => $token]);
        if(!empty($user) ) {
            $carbonNow = Carbon::now();
            $carbonLastAuth = Carbon::parse($user->last_activity);
            $diffSecond = $carbonLastAuth->diffInSeconds($carbonNow);

            $carbonLastAuthStartDay = $carbonLastAuth->startOfDay();
            $carbonNowAuthStartDay = $carbonNow->startOfDay();
            // Если с последней фиксации активности прошло не меньше минуты или начался новый день то фиксируем активность
            if ($user->last_activity == NULL || $diffSecond >= env('ACCOUNT_UPDATE_LAST_ACTIVITY_TIMEOUT') || $carbonLastAuthStartDay->notEqualTo($carbonNowAuthStartDay)) {
                $user->last_activity = gmdate('Y-m-d H:i:s');
                $user->save();
            }
        }
        return $user;
    }

    public function getId(){
        return $this->getPrimaryKey();
    }

    public function getAuthKey(){
        return $this->auth_token;
    }

    public function validateAuthKey($authKey){
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Установка серверного пользователя
     */
    public static function setSeverIdentity(){
        // Если таблица существует присваеваем пользователя
        try {
            if(Yii::$app->db->schema->getTableSchema(self::tableName())) {
                $identity = self::findIdentity(1);
                if ($identity) {
                    Yii::$app->user->setIdentity($identity);
                }
            }
        } catch (\Exception $e) {
            return null;
        }
    }
}
