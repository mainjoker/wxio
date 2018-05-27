<?php
/**
 * Created by PhpStorm.
 * User: hkb
 * Date: 2018/4/12
 * Time: 23:24
 */

namespace app\lib\validate;

use app\lib\exception\ParamsException;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        $params = request()->param();
        $res = $this->batch()->check($params);
        if (!$res) {
            $error = $this->getError();
            $error = is_array($error) ? implode(';', $error) : $error;
            throw new ParamsException(
                [ 'msg' => $error]
            );
        }
        return true;
    }
    
}