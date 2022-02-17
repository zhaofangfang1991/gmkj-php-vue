<?php
/**
 *Author: zff
 */

namespace app\lib\exception;


class MaintenanceException extends BaseException
{
    public $code = 401;
    public $msg = '保养单错误，请检查参数';
    public $errorCode = 90010 ;
}