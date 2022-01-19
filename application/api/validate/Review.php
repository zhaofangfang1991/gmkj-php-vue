<?php
/**
 *Author: zff
 */

namespace app\api\validate;


class Review extends BaseValidate
{
    protected $rule = [
        'review_result' => 'require|in: 1,2',
        'content' => 'require|isNotEmpty',
//        'token' => 'isNotEmpty'
    ];
}