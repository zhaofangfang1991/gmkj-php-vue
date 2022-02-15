<?php
/**
 *Author: zff
 */

namespace app\api\validate;


class Repair extends BaseValidate
{
    protected $rule = [
        'tool_id' => 'require|isNotEmpty',
        'repair_id' => 'require|isNotEmpty',
        'cause_content' => 'require|isNotEmpty',
    ];
}