<?php

namespace pancakes\accounts\repository\responseModels;

use pancakes\accounts\AccountReferenceData;
use pancakes\accounts\repository\ar\AccountsAR;
use pancakes\filestorage\repository\responseModels\PublicFilestorageObjectsModel;
use yii\db\ActiveQuery;

class ManageAccountsModel extends AccountsAR
{

    public $requestedThumbsWidths = [150];

    /**
    * @inheritdoc
    */
    public function fields()
    {
        $fields['id'] = function(){
            return $this->id;
        };
        $fields['public_key'] = function(){
            return $this->public_key;
        };
        $fields['username'] = function(){
            return $this->username;
        };
        $fields['email'] = function(){
            return $this->email;
        };
        $fields['status_of_email_confirm'] = function(){
            return $this->status_of_email_confirm;
        };
        $fields['status'] = function(){
            return $this->status;
        };
        $fields['role'] = function(){
            return $this->role;
        };
        $fields['avatar_obj'] = function(){
            array_map(function($fsObject) {
                /** @var $fsObject PublicFilestorageObjectsModel */
                $fsObject->requestedThumbsWidths = $this->requestedThumbsWidths;
            }, $this->avatars);
            return $this->avatars;
        };
        $fields['avatar_url'] = function(){
            if (!empty($this->avatar)) {
                return $this->avatar->getUrl();
            }
            return null;
        };
        $fields['created_at'] = function(){
            return $this->created_at;
        };
        $fields['updated_at'] = function(){
            return $this->updated_at;
        };
        $fields['last_activity'] = function(){
            if(empty($this->last_activity)) return null;
            return (strtotime(gmdate("Y-m-d H:i:s")) - strtotime($this->last_activity));
        };
        return $fields;
    }

    /**
     * @throws \Exception
     */
    public function fieldsReferenceData(){
        return [
            'status' => AccountReferenceData::getAccountStatuses(),
            'role' => AccountReferenceData::getAccountRoles(),
        ];
    }

    /**
    * @inheritdoc
    */
    public function extraFields()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getAvatars()
    {
        return $this->hasMany(PublicFilestorageObjectsModel::class, ['id' => 'avatar_obj_id'])
            ->joinWith(['thumbs' => function ($q) {
                /** @var $q ActiveQuery */
                $q->onCondition(['IN', 'filestorage_thumbnails.width', $this->requestedThumbsWidths]);
            }]);
    }
}
