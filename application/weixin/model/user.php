<?php
/**
 * Created by PhpStorm.
 * User: hkb
 * Date: 2018/4/14
 * Time: 11:19
 */

namespace app\weixin\model;


use think\Model;

class user extends Model
{
    public static function getUserByOpenid($openid){
        $res=user::where('openid','=',$openid)->find();
        return $res;
    }
}