<?php
/**
 *Author: zff
 */

namespace app\api\validate;


class PartsUse extends BaseValidate
{
    protected $rule = [
        'current_count' => 'require|number',
        'num' => 'require|number|gt:1',
        'account_id' => 'require|isNotEmpty',
    ];
}