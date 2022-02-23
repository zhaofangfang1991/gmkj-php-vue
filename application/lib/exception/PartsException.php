<?php
/**
 *Author: zff
 */

namespace app\lib\exception;


class PartsException extends BaseException
{
    public $code = 401;
    public $msg = '备件信息错误，请检查参数';
    public $errorCode = 90011;
}