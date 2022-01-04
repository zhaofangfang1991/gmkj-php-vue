<?php
/**
 *Author: zff
 */

namespace app\api\validate;


class ToolNew extends BaseValidate
{
    protected $rule = [
        'status' => 'isPositiveInteger|isNotEmpty|require',
        'name' => 'require',
        'status' => 'isNotEmpty',
        'charge_id' => 'isPositiveInteger',
        'agency_id' => 'isPositiveInteger',
        'agency_username' => 'isNotEmpty',
        'agency_telnumber' => 'isMobile',
        'agency_gender' => 'isGender',
        'agency_manufacturer' => 'isNotEmpty',
        'agency_agent' => 'isNotEmpty'
    ];
}