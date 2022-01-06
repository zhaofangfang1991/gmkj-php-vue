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
        'type' => 'in: 1,2,4,8,12,10,9,6,5,3,14,13,7,11,15',
        'role_type' => 'array|require|isNotEmpty'
    ];

    protected $message = [
        'telnumber.require' => '手机号已存在',
    ];
}