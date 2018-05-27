<?php
/**
 * Created by PhpStorm.
 * User: hkb
 * Date: 2018/4/12
 * Time: 23:29
 */

namespace app\lib\exception;

use Exception;
use think\exception\Handle;

class ExceptionHandle extends Handle
{
    private $msg;
    private $code;
    private $errCode;
    
    public function render(Exception $e)
    {
        if ($e instanceof BaseException) {
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errCode = $e->errCode;
        } else {
            if (config('app_debug')) {
                return parent::render($e);
            }
            $this->code = 500;
            $this->msg = '服务器内部错误';
            $this->errCode = 999;
        }
        $url = request()->url();
        $res = [ 'msg' => $this->msg, 'errCOde' => $this->errCode, 'url' => $url, ];
        return json($res, $this->code);
    }
}