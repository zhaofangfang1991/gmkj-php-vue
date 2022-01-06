<?php
/**
 *Author: zff
 */

namespace app\lib\exception;


class FileException extends BaseException
{
    public $code = 403;
    public $msg = '上传失败，请重试';
    public $errorCode = 90003;
}