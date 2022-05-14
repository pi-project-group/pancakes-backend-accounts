<?php

namespace pancakes\accounts\services;

use Exception;
use pancakes\accounts\repository\ar\AccountsAR;
use pancakes\accounts\repository\ar\AccountsStatusLogAR;
use pancakes\accounts\repository\models\AccountChangeStatusModel;
use pancakes\accounts\repository\search\AccountsStatusLogSearchModel;
use pancakes\kernel\base\utils\OtherUtils;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * @property AccountsStatusLogAR $dataGateway
 */
class AccountsStatusService
{
    protected $dataGateway;

    public function __construct(AccountsStatusLogAR $dataGateway = null)
    {
        $this->dataGateway = !empty($dataGateway) ? $dataGateway : new AccountsStatusLogAR();
    }

    /**
     * @param AccountsStatusLogSearchModel $searchModel
     * @return ActiveDataProvider
     */
    public function getAccountStatusLogDataProvider(AccountsStatusLogSearchModel $searchModel) {
        return $searchModel->getActiveDataProvider();
    }

    /**
     * Смена статуса пользователя
     * @param AccountChangeStatusModel $dataModel
     * @param AccountsAR $account
     * @return AccountsStatusLogAR
     * @throws \Exception
     */
    public function changeAccountStatus(AccountChangeStatusModel $dataModel, AccountsAR $account) {
        $account->refresh();
        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            $account->status = $dataModel->status;

            if($account->status != $account::STATUS_ACTIVE) {
                $account->auth_token = null;
            } elseif (empty($account->auth_token)) {
                $account->auth_token = OtherUtils::generateToken();;
            }

            $account->save(false);
            $result = $this->addStatusLog($account->id, $dataModel->status, $dataModel->comment);
            $transaction->commit();
            return $result;
        }
        catch(Exception $e)
        {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Добавление в журнал первого статуса пользователя.
     * @param AccountsAR $account
     * @return AccountsStatusLogAR
     */
    public function loggedFirstAccountStatus(AccountsAR $account) {
        if (!AccountsStatusLogAR::find()->where(['account_id' => $account->id])->exists()) {
            return $this->addStatusLog($account->id, $account->status);
        }
        return null;
    }

    /**
     * @param $accountId
     * @param $status
     * @param string $comment
     * @return AccountsStatusLogAR
     */
    protected function addStatusLog($accountId, $status, $comment = '') {
        /** @var $model AccountsStatusLogAR */
        $model = new $this->dataGateway();
        $model->account_id = $accountId;
        $model->status = $status;
        $model->comment = $comment;
        $model->save(false);
        return $model;
    }
    /**
     * Возвращает последнюю запись лога смены пользовательского статуса
     * @param $accountId
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getLastStatusLog($accountId) {
        return $this->dataGateway::find()->where(['account_id' => $accountId])->orderBy(['id' => SORT_DESC])->one();
    }
}
