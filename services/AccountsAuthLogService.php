<?php

namespace pancakes\accounts\services;

use DeviceDetector\DeviceDetector;
use pancakes\accounts\repository\ar\AccountsAR;
use pancakes\accounts\repository\ar\AccountsAuthLogAR;
use pancakes\accounts\repository\search\AccountsAuthLogSearchModel;
use yii\data\ActiveDataProvider;
use yii\web\Request;

/**
 * @property AccountsAuthLogAR $dataGateway
 */
class AccountsAuthLogService
{
    protected $dataGateway;

    public function __construct(AccountsAuthLogAR $dataGateway = null)
    {
        $this->dataGateway = !empty($dataGateway) ? $dataGateway : new AccountsAuthLogAR();
    }

    /**
     * @param AccountsAuthLogSearchModel $searchModel
     * @return ActiveDataProvider
     */
    public function getAccountAuthLogDataProvider(AccountsAuthLogSearchModel $searchModel) {
        return $searchModel->getActiveDataProvider();
    }

    /**
     * @param AccountsAR $account
     * @param Request $request
     * @return AccountsAuthLogAR
     */
    public function addAuthLog(AccountsAR $account, Request $request) {
        $dd = new DeviceDetector($request->userAgent);
        $dd->parse();

        /** @var $authLog AccountsAuthLogAR */
        $authLog = new $this->dataGateway();
        $authLog->account_id = $account->id;
        $authLog->user_agent = $request->userAgent;
        $authLog->device = $dd->getDeviceName();
        $authLog->os = !empty($dd->getOs()) ? $dd->getOs()['name'] . ' ' . $dd->getOs()['version'] : '';
        $authLog->browser = !empty($dd->getClient()) ? $dd->getClient()['name'] . ' ' . $dd->getClient()['version'] : '';
        $authLog->inet_ip = inet_pton($request->getUserIP());
        $authLog->referer_url = $request->cookies->get(env('REFERER_COOKIE_NAME'));
        $authLog->save(false);
        return $authLog;
    }

}
