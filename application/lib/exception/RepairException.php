<?php
/**
 *Author: zff
 */

namespace app\lib\exception;


class RepairException extends BaseException
{
    public $code = 404;
    public $msg = '维修单错误，请检查传参';
    public $errorCode = 90009;
}