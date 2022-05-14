<?php
namespace pancakes\accounts\controllers\validators;

use pancakes\accounts\repository\models\AccountChangeStatusModel;
use Yii;

class AccountChangeStatusFrom extends AccountChangeStatusModel
{
    public $status;
    public $comment;

    /**
     * @inheritdoc
     */
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'comment'], 'required'],
            [['status'], 'number'],
            [['comment'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'status' => Yii::t('package-accounts', 'Статус'),
            'comment' => Yii::t('package-accounts', 'Комментарий')
        ];
    }
}
