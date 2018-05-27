<?php
/**
 * Created by PhpStorm.
 * User: hkb
 * Date: 2018/4/12
 * Time: 23:55
 */

namespace app\lib\validate;


class CodeValidate extends BaseValidate
{
    protected $rule = [ 'code' => 'require'];
    protected $message = [ 'code.require' => '需要code参数哦'];
}