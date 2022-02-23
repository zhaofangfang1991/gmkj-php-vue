<?php
/**
 *Author: zff
 */

namespace app\api\validate;


class Parts extends BaseValidate
{
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'no' => 'require|isNotEmpty',
        'pattern' => 'require|isNotEmpty',
        'manufacturer' => 'isNotEmpty',
        'count' => 'require|number|gt:1',
        'unit' => 'require|number|in:1,2,3',
        'content' => 'isNotEmpty'
    ];
}