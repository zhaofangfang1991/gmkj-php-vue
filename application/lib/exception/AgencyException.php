<?php
/**
 *Author: zff
 */

namespace app\lib\exception;


class AgencyException extends BaseException
{
    public $code = 404;
    public $msg = '添加代理商出错';
    public $errorCode = 90006;
}