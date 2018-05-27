<?php
/**
 * Created by PhpStorm.
 * User: hkb
 * Date: 2018/4/14
 * Time: 11:44
 */

namespace app\lib\exception;


class CacheException extends BaseException
{
    public $msg = '缓存失败，服务器内部错误';
    public $code = 501;
    public $errCode = 10000;
}