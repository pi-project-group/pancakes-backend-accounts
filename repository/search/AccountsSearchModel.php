<?php
namespace pancakes\accounts\repository\search;

use pancakes\kernel\base\SearchModel;
use pancakes\accounts\repository\ar\AccountsAR;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class AccountsSearchModel extends SearchModel
{
    public $status;
    public $createdAtFrom;
    public $createdAtTo;
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
        $rules = [
            [['status', 'createdAtFrom', 'createdAtTo', 'search'], 'safe']
        ];
        return array_merge(parent::rules(), $rules);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'createdAtFrom' => Yii::t('package-accounts', 'Дата создания'),
            'createdAtTo' => Yii::t('package-accounts', 'Дата создания'),
            'search' => Yii::t('package-accounts', 'Поиск')
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributePlaceholders()
    {
        return [
            'search' => Yii::t('package-accounts', 'Введите email, username или публичный ключ')
        ];
    }


    /**
     * @return ActiveQuery
     */
    public function getSearchQuery(){
        $query = $this->dataGateway::find()
            ->joinWith(['avatar', 'avatars', 'profile'])
            ->andWhere(['>', 'accounts.id', 1])
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['>=', 'DATE(accounts.created_at)', $this->createdAtFrom])
            ->andFilterWhere(['<=', 'DATE(accounts.created_at)', $this->createdAtTo]);
        if (!empty($this->search)) {
            $query->andWhere(['OR',
                ['LIKE', 'accounts.email', $this->search],
                ['LIKE', 'accounts.username', $this->search],
                ['accounts.public_key' => $this->search]
            ]);
        }
        return $query;
    }

    /**
     * @return AccountsSearchModel|ActiveDataProvider
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
                        'asc' => ['accounts.created_at' => SORT_ASC],
                        'desc' => ['accounts.created_at' => SORT_DESC],
                    ]
                ],
            ]
        ]);
    }
}
