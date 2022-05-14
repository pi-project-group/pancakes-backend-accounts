<?php
namespace pancakes\accounts\repository\search;

use pancakes\kernel\base\SearchModel;
use pancakes\accounts\repository\ar\AccountsAuthLogAR;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;

class AccountsAuthLogSearchModel extends SearchModel
{
    public $accountId;
    public $accountSearch;
    public $ip;
    public $userAgent;
    public $createdAtFrom;
    public $createdAtTo;

    protected $dataGateway;

    public function __construct(AccountsAuthLogAR $dataGateway = null)
    {
        parent::__construct();
        $this->dataGateway = !empty($dataGateway) ? $dataGateway : new AccountsAuthLogAR();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['accountId', 'accountSearch', 'ip', 'userAgent', 'createdAtFrom', 'createdAtTo'], 'safe']
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
            ->joinWith(['account'])
            ->andFilterWhere([
                'accounts_auth_log.account_id' => $this->accountId
            ]);
        $query->andFilterWhere(['LIKE', 'user_agent', $this->userAgent]);
        if (!empty($this->accountSearch)) {
            $query->andWhere(['OR',
                ['LIKE', 'accounts.email', $this->accountSearch],
                ['LIKE', 'accounts.username', $this->accountSearch],
                ['LIKE', 'accounts.public_key', $this->accountSearch]
            ]);
        }
        if(!empty($this->ip)) {
            $query->andWhere(['inet_ip' => new Expression("INET6_ATON('$this->ip')")]);
        }
        if(!empty($this->createdAtFrom)) {
            $query->andWhere(['>=', 'accounts_auth_log.created_at', $this->dateTimeToUtcFrom($this->createdAtFrom)]);
        }
        if(!empty($this->createdAtTo)) {
            $query->andWhere(['<=', 'accounts_auth_log.created_at', $this->dateTimeToUtcTo($this->createdAtTo)]);
        }
        return $query;
    }

    /**
     * @return AccountsAuthLogSearchModel|ActiveDataProvider
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
