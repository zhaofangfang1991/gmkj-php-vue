<?php
/**
 *Author: zff
 */

namespace app\api\validate;


class RepairEdit extends BaseValidate
{
    protected $rule = [
        'repair_content' => 'require|isNotEmpty',
        'reason' => 'require|in:1,2,3,4,5',
        'status' => 'require|in:1,2,3',
    ];
}