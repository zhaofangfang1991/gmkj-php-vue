<?php
/**
 * Author: zff
 * Date: 2017/2/14 我去，情人节，886214
 * Time: 1:03
 */

namespace app\lib\exception;


class ToolException extends BaseException
{
    public $code = 401;
    public $msg = '设备新增出错，请检查参数';
    public $errorCode = 90001;
}