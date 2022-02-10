<?php
/**
 *Author: zff
 */

namespace app\api\validate;


class AccountNew extends BaseValidate
{
    protected $rule = [
        'username' => 'require|isNotEmpty|max:5',
        'telnumber' => 'require|isMobile|unique:Account',
        'password' => 'require|max:10',
        'gender' => 'isGender',
        'type' => 'in: 1,2,4,6,5,3,7',
        'role_type' => 'array|require|isNotEmpty'
    ];

    protected $message = [
        'telnumber.require' => '手机号已存在',
    ];
}