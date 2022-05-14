<?php

namespace pancakes\accounts\modules\suspicious_activity\services;

use pancakes\accounts\modules\suspicious_activity\repository\ar\AccountsSuspiciousActivityTypesAR;
use pancakes\kernel\base\exceptions\ObjectNotFoundException;

/**
 * @property AccountsSuspiciousActivityTypesAR $dataGateway
 */
class AccountsSuspiciousActivityTypesService
{
    protected $dataGateway;

    public function __construct(AccountsSuspiciousActivityTypesAR $dataGateway = null)
    {
        $this->dataGateway = !empty($dataGateway) ? $dataGateway : new AccountsSuspiciousActivityTypesAR();
    }

    /**
     * @param $id
     * @return AccountsSuspiciousActivityTypesAR
     * @throws ObjectNotFoundException
     */
    public function findById($id) {
        $model = $this->dataGateway::findOne($id);
        if (empty($model)){
            throw new ObjectNotFoundException($id);
        }
        return $model;
    }
}
