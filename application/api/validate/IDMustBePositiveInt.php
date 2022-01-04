<?php
/**
 * Author: zff
 * Date: 2017/2/18
 * Time: 12:35
 */
namespace app\api\validate;

class IDMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
    ];

//    可以针对每个限制写特定的提示信息
//    protected $message = [
//        'id.require' => '请填写栏目名称',
//        'id.isPositiveInteger' => '栏目已存在',
//    ];
}
