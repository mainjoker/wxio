<?php
/**
 * Created by PhpStorm.
 * User: hkb
 * Date: 2018/4/12
 * Time: 23:43
 */

namespace app\lib\exception;


class ParamsException extends BaseException
{
    public $msg = '参数错误';
    public $code = 200;
    public $errCode = 20000;
}