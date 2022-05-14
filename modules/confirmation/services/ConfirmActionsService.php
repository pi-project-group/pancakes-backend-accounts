<?php

namespace pancakes\accounts\modules\confirmation\services;

use pancakes\kernel\base\exceptions\ObjectNotFoundException;
use pancakes\kernel\base\utils\OtherUtils;
use pancakes\accounts\modules\confirmation\repository\ar\ConfirmActionsAR;
use pancakes\accounts\modules\confirmation\repository\responseModels\ConfirmActionsModel;

/**
 * @property ConfirmActionsAR $dataGateway
 */
class ConfirmActionsService
{
    protected $dataGateway;

    public function __construct(ConfirmActionsModel $dataGateway = null)
    {
        $this->dataGateway = !empty($dataGateway) ? $dataGateway : new ConfirmActionsModel();
    }

    /**
     * @param array $data
     * @param int|null $account_id
     * @param $confirmed
     * @return ConfirmActionsAR
     * @throws \Exception
     */
    public function create(array $data, int $account_id, $confirmed = false) {
        $code = mt_rand(100000, 999999);

        /** @var $model ConfirmActionsAR */
        $model = new $this->dataGateway();
        $model->public_key = OtherUtils::generatePublicKey();
        $model->secret_key = password_hash($code, PASSWORD_BCRYPT, ['cost' => 5]);
        $model->data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $model->account_id = $account_id;
        if(!empty($confirmed)) {
            $model->confirmed_at = gmdate('Y-m-d H:i:s');
        }
        $model->save(false);

        $model->code = $code;
        return $model;
    }

    /**
     * @param string $public_key
     * @param int $account_id
     * @return ConfirmActionsAR
     * @throws ObjectNotFoundException
     */
    public function find(string $public_key, int $account_id) {
        /** @var $model ConfirmActionsAR */
        $model = $this->dataGateway::find()->where(['AND',
            ['public_key' => $public_key],
            ['account_id' => $account_id],
            ['IS', 'confirmed_at', null]
        ])->one();

        if(empty($model)) {
            throw new ObjectNotFoundException($public_key);
        }
        return $model;
    }

    public function confirmByCode(ConfirmActionsAR $confirmActionModel, $code) {
        $confirmActionModel->updateCounters(['attempts' => 1]);
        if(password_verify($code, $confirmActionModel->secret_key)) {
            $confirmActionModel->confirmed_at = gmdate('Y-m-d H:i:s');
            $confirmActionModel->save(true);
            return true;
        }
        $confirmActionModel->save(true);
        return false;
    }
}
