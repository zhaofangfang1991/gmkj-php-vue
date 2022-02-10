<?php
/**
 *Author: zff
 */

namespace app\api\validate;


class AccountUpdate extends BaseValidate
{
    protected $rule = [
        'username' => 'require|isNotEmpty|max:5',
        'telnumber' => 'require|isMobile',
        'gender' => 'isGender',
        'type' => 'in: 1,2,4,6,5,3,7',
        'role_type' => 'array|require|isNotEmpty',
        'password' => 'length:0,15'
    ];

}