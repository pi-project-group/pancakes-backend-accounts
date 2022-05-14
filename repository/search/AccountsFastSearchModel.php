<?php
namespace pancakes\accounts\repository\search;

use pancakes\kernel\base\SearchModel;
use pancakes\accounts\repository\ar\AccountsAR;
use Yii;
use yii\db\ActiveQuery;

/**
 * Модель быстрого поиска пользователя
 */
class AccountsFastSearchModel extends SearchModel
{
    public $search;

    protected $dataGateway;

    public function __construct(AccountsAR $dataGateway = null)
    {
        parent::__construct();
        $this->dataGateway = !empty($dataGateway) ? $dataGateway : new AccountsAR();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = array_merge(parent::rules(), [
            [['search'], 'string'],
            'pageSize' => ['pageSize', 'integer', 'min' => 10, 'max' => 10],

        ]);
        unset($rules['pageSize']);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'search' => Yii::t('package-accounts', 'Поиск')
        ];
    }

    public function getSortRules()
    {
        return [
            'defaultOrder' => ['username' => SORT_DESC],
            'attributes' => [
                'username' => [
                    'asc' => ['accounts.username' => SORT_ASC],
                    'desc' => ['accounts.username' => SORT_DESC],
                ]
            ]
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getSearchQuery(){
        $query = $this->dataGateway::find()->joinWith(['avatar']);
        if (!empty($this->search)) {
            $query->andWhere(['OR',
                ['LIKE', 'accounts.email', $this->search],
                ['LIKE', 'accounts.username', $this->search],
                ['LIKE', 'accounts.public_key', $this->search]
            ]);
        }
        return $query;
    }
}
