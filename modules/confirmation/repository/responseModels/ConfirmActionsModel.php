<?php

namespace pancakes\accounts\modules\confirmation\repository\responseModels;

use pancakes\accounts\modules\confirmation\repository\ar\ConfirmActionsAR;
use Yii;

/**
 * Модель объекта для подтверждения действия.
 * Для подтверждения отправляется public_key и secret_key из email в необходимое действие.
*/
class ConfirmActionsModel extends ConfirmActionsAR
{
    /**
    * @inheritdoc
    */
    public function fields()
    {
        $fields['public_key'] = function(){
            return $this->public_key;
        };
        $fields['created_at'] = function(){
            return $this->created_at;
        };
        $fields['is_confirmed'] = function(){
            return !empty($this->confirmed_at);
        };
        $fields['confirmed_at'] = function(){
            return $this->confirmed_at;
        };
        return $fields;
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'is_confirmed' => Yii::t('package-accounts', 'Подтвержден'),
        ]);
    }
}
