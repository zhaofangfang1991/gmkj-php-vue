<?php
/**
 * Author: zff
 * Date: 2017/2/23
 * Time: 21:56
 */

namespace app\api\validate;


class IsValidUserToken extends BaseValidate
{
    protected $rule = [
        'token' => 'isValidUserToken'
    ];
}