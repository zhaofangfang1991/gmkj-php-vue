<?php
/**
 *Author: zff
 */

namespace app\api\validate;


class ToolNew extends BaseValidate
{
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'sort' => 'number',
        'address' => 'number',
        'voltage' => 'number',
        'no' => 'require|isNotEmpty',
        'runing_status' => 'isPositiveInteger|isNotEmpty|require',
        'service_period' => 'number',
        'pattern' => 'isNotEmpty',
        'electric_current' => 'number',
        'power' => 'number',
        'begin_time' => 'date',
        'charge_id' => 'isPositiveInteger|require',
        'level' => 'in:1,0',
        // 子工具相关参数-->从客户端接收后，存成子工具
        'subName' => 'isNotEmpty',
        'subNo' => 'isNotEmpty',
        'subSort' => 'number',
        /**
        文件->从客户端拿到此字段后，等工具存表成功后，
         * 循环根据pic更新resource表
         */
        'pics' => 'array',
        // 供应商选择
        'agency_id' => 'isPositiveInteger',
    ];
}