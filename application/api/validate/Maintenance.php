<?php
/**
 *Author: zff
 */

namespace app\api\validate;


class Maintenance extends BaseValidate
{
    protected $rule = [
        'level' => 'require|in: 1,2,3,4',
        'content' => 'require|isNotEmpty',
    ];
}