<?php

namespace pancakes\accounts\modules\suspicious_activity\services;

use Exception;
use pancakes\accounts\modules\suspicious_activity\controllers\validators\AccountsSuspiciousActivityInspectFrom;
use pancakes\accounts\modules\suspicious_activity\repository\ar\AccountsSuspiciousActivityAR;
use pancakes\accounts\repository\ar\AccountsAR;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * @property AccountsSuspiciousActivityAR $dataGateway
 */
class AccountsSuspiciousActivityService
{
    protected $dataGateway;

    public function __construct(AccountsSuspiciousActivityAR $dataGateway = null)
    {
        $this->dataGateway = !empty($dataGateway) ? $dataGateway : new AccountsSuspiciousActivityAR();
    }

    /**
     * @param $id
     * @return AccountsSuspiciousActivityAR
     * @throws ObjectNotFoundException
     */
    public function findById($id) {
        $model = $this->dataGateway::findOne($id);
        if (empty($model)){
            throw new ObjectNotFoundException($id);
        }
        return $model;
    }

    /**
     * Возвращает последнюю проинспектированную запись
     * @param $account_id
     * @param $type_id
     * @return AccountsSuspiciousActivityAR
     */
    public function findLastInspected(int $account_id, int $type_id) {
        /** @var $model AccountsSuspiciousActivityAR */
        $model = $this->dataGateway::find()
            ->where([
                'type_id' => $type_id,
                'account_id' => $account_id,
                'status' => AccountsSuspiciousActivityAR::STATUS_CHECKED
            ])
            ->orderBy(['checked_at' => SORT_DESC])
            ->one();
        return $model;
    }

    /**
     * Возвращает последнюю запись которая ожидает проверку
     * @param $account_id
     * @param $type_id
     * @return AccountsSuspiciousActivityAR
     */
    public function findLastAwaiting($account_id, $type_id) {
        /** @var $model AccountsSuspiciousActivityAR */
        $model = $this->dataGateway::find()
            ->where([
                'type_id' => $type_id,
                'account_id' => $account_id,
                'status' => AccountsSuspiciousActivityAR::STATUS_AWAITING
            ])
            ->orderBy(['checked_at' => SORT_DESC])
            ->one();
        return $model;
    }

    /**
     * Обработчик фиксации подозрительной активности
     * @param AccountsAR $account
     * @param $type_id
     * @param $count_actions
     * @return mixed|AccountsSuspiciousActivityAR|null
     * @throws Exception
     */
    public function activityFixationHandler(AccountsAR $account, $type_id, $count_actions){

        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            $suspiciousActivityTypesService = new AccountsSuspiciousActivityTypesService();
            try {
                $suspiciousActivityType = $suspiciousActivityTypesService->findById($type_id);
            } catch (ObjectNotFoundException $e) {
                Yii::error('Не удалось определить тип подозрительной активности id:' . $type_id);
                return null;
            }

            // Если кол-во дейтвий не превысело порог для фиксации то ничего не делаем
            if ($suspiciousActivityType->activity_threshold > $count_actions) {
                return null;
            }

            if($suspiciousActivityType->is_auto_ban) {
                // TODO Описать логику автоматической блокировки аккаунта
            }

            $model = $this->findLastAwaiting($account->id, $suspiciousActivityType->id);
            if (empty($model)) {
                $model = new $this->dataGateway();
                $model->type_id = $suspiciousActivityType->id;
                $model->account_id = $account->id;
                $model->status = AccountsSuspiciousActivityAR::STATUS_AWAITING;
            }
            /** @var $model AccountsSuspiciousActivityAR */
            $model->count = $count_actions;
            $model->save(false);
            $transaction->commit();
            return $model;
        }
        catch(Exception $e)
        {
            $transaction->rollback();
            throw $e;
        }
    }

    /**
     * @param AccountsSuspiciousActivityAR $activityModel
     * @param AccountsSuspiciousActivityInspectFrom $form
     * @return AccountsSuspiciousActivityAR
     * @throws \Exception
     */
    public function inspect(AccountsSuspiciousActivityAR $activityModel, AccountsSuspiciousActivityInspectFrom $form) {
        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            $activityModel->refresh();
            if (!empty($activityModel->checked_account_id) && $activityModel->checked_account_id != Yii::$app->user->id) {
                throw new ForbiddenHttpException(Yii::t('package-accounts', 'Вам нельзя производить это действие'));
            }

            $activityModel->status = AccountsSuspiciousActivityAR::STATUS_CHECKED;
            $activityModel->checked_account_id = Yii::$app->user->id;

            if (empty($activityModel->checked_at)) {
                $activityModel->checked_at = gmdate('Y-m-d H:i:s');
            }

            foreach ($form->loadParams as $name => $value) {
                switch ($name) {
                    case 'comment_internal':
                        $activityModel->comment_internal = $form->comment_internal;
                        break;
                }
            }

            if(!empty($form->account_status)) {
                $account = $activityModel->account;
                if($form->account_status == AccountsAR::STATUS_ACTIVE &&  $account->status != AccountsAR::STATUS_ACTIVE) {
                    // TODO Описать логику активации аккаунта
                } elseif ($form->account_status == AccountsAR::STATUS_BANED &&  $account->status != AccountsAR::STATUS_BANED) {
                    // TODO Описать логику блокировки аккаунта
                }
            }

            $activityModel->save(false);
            $transaction->commit();
            return $activityModel;
        }
        catch(Exception $e)
        {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }
}
