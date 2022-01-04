<?php
/**
 *Author: zff
 */

namespace app\lib\exception;


class AccountException extends BaseException
{
    public $code = 404;
    public $msg = '用户账号不存在，请检查传参';
    public $errorCode = 60003;
}