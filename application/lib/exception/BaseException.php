<?php
/**
 * Created by PhpStorm.
 * User: hkb
 * Date: 2018/4/12
 * Time: 23:26
 */

namespace app\lib\exception;


use think\Exception;

class BaseException extends Exception
{
    public $msg = '基本错误';
    public $code = 200;
    public $errCode = 300;
    
    public function __construct($params=[])
    {
        if (!is_array($params)) {
            return;
        }
        if (array_key_exists('msg',$params)){
        $this->msg = $params['msg'];
    }
         if (array_key_exists('code',$params)){
        $this->code = $params['code'];
    }
     if (array_key_exists('errCode',$params)){
        $this->errCode = $params['errCode'];
    }
    }
}