<?php
/**
 *Author: zff
 */

namespace app\api\validate;


class Agency extends BaseValidate
{
    protected $rule = [
        'username' => 'require|isNotEmpty|max:50',
        'telnumber' => 'require|isMobile|unique:Agency',
        'gender' => 'require|isNotEmpty',
        'manufacturer' => 'require|isNotEmpty',
        'agent' => 'require|isNotEmpty',
    ];
}