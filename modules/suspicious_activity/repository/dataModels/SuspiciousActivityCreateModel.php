<?php
namespace pancakes\accounts\modules\suspicious_activity\repository\dataModels;

use pancakes\kernel\base\FormModel;

class SuspiciousActivityCreateModel extends FormModel
{
    public $metadata;
    public $status;
    public $checked_account_id;
    public $comment_internal;
}
