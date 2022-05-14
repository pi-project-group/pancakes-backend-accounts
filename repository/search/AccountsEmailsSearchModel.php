<?php
namespace pancakes\accounts\repository\search;

use pancakes\kernel\base\SearchModel;
use pancakes\accounts\repository\ar\AccountsEmailsLogAR;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class AccountsEmailsSearchModel extends SearchModel
{
    public $accountId;

    protected $dataGateway;

    public function __construct(AccountsEmailsLogAR $dataGateway = null)
    {
        parent::__construct();
        $this->dataGateway = !empty($dataGateway) ? $dataGateway : new AccountsEmailsLogAR();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['accountId'], 'safe']
        ];
        return array_merge(parent::rules(), $rules);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * @return ActiveQuery
     */
    public function getSearchQuery(){
        $query = $this->dataGateway::find()
            ->joinWith(['account', 'author'])
            ->andFilterWhere([
                'account_id' => $this->accountId
            ]);
        return $query;
    }

    /**
     * @return AccountsEmailsSearchModel|ActiveDataProvider
     */
    public function getActiveDataProvider(){
        if(!$this->validate()) {
            return $this;
        }
        return new ActiveDataProvider([
            'query' => $this->getSearchQuery(),
            'pagination' => [
                'pageSize' => $this->pageSize,
            ],
            'sort'=> [
                'defaultOrder' => ['createdAt' => SORT_DESC],
                'attributes' => [
                    'createdAt' => [
                        'asc' => ['created_at' => SORT_ASC],
                        'desc' => ['created_at' => SORT_DESC],
                    ]
                ],
            ]
        ]);
    }
}
