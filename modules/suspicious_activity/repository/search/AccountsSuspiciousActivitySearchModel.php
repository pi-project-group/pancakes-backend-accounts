<?php
namespace pancakes\accounts\modules\suspicious_activity\repository\search;

use pancakes\accounts\modules\suspicious_activity\repository\ar\AccountsSuspiciousActivityAR;
use pancakes\kernel\base\SearchModel;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Модель поиска специальных страниц
 */
class AccountsSuspiciousActivitySearchModel extends SearchModel
{
    public $id;
    public $type;
    public $status;
    public $account_search;
    public $created_at;

    protected $dataGateway;

    public function __construct(AccountsSuspiciousActivityAR $dataGateway = null)
    {
        parent::__construct();
        $this->dataGateway = !empty($dataGateway) ? $dataGateway : new AccountsSuspiciousActivityAR();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['id'], 'integer'],
            [['account_search', 'created_at'], 'string'],
            [['type', 'status'], 'integer']
        ];
        return array_merge(parent::rules(), $rules);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'account' => Yii::t('package-accounts', 'Пользователь'),
            'type' => Yii::t('package-accounts', 'Тип активности'),
            'status' => Yii::t('package-accounts', 'Статус')
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getSearchQuery(){
        $query = $this->dataGateway::find()
            ->joinWith(['account', 'checkedAccount'])
            ->andFilterWhere(['accounts_suspicious_activity.id' => $this->id])
            ->andFilterWhere(['accounts_suspicious_activity.type_id' => $this->type])
            ->andFilterWhere(['accounts_suspicious_activity.status' => $this->status]);
        if(!empty($this->account_search)){
            if(is_numeric($this->account_search)){
                $query->andFilterWhere(['user.id' => $this->account_search]);
            }
            else{
                $query->andFilterWhere(['OR',
                    ['LIKE', 'user.username', $this->account_search],
                    ['LIKE', 'user.email', $this->account_search],
                ]);
            }
        }
        if(!empty($this->created_at)){
            $query->andWhere(['AND',
                ['>=', 'accounts_suspicious_activity.created_at', $this->dateTimeToUtcFrom($this->created_at)],
                ['<=', 'accounts_suspicious_activity.created_at', $this->dateTimeToUtcTo($this->created_at)]
            ]);
        }
        return $query;
    }

    /**
     * @return AccountsSuspiciousActivitySearchModel|ActiveDataProvider
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
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => [
                    'created_at' => [
                        'asc' => ['accounts_suspicious_activity.created_at' => SORT_ASC],
                        'desc' => ['accounts_suspicious_activity.created_at' => SORT_DESC],
                    ]
                ],
            ]
        ]);
    }
}
