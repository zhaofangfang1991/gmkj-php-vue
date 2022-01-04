<?php
/**
 * Author: zff
 * Date: 2017/2/18
 * Time: 12:35
 */
namespace app\api\validate;

class AccountTokenGet extends BaseValidate
{
    protected $rule = [
        'username' => 'require|isNotEmpty',
        'password' => 'require|isNotEmpty',
    ];
}
