<?php
/**
 * Created by PhpStorm.
 * User: hkb
 * Date: 2018/4/14
 * Time: 8:49
 */

namespace app\weixin\controller;


use think\facade\Cache;
use think\facade\Config;
use think\facade\Cookie;

class Test
{
    public function why(){
    
        $test1=Cookie::get('test1');
        $test2=Cookie::get('test2');
    
//        var_dump($test1);
//        var_dump($test2);
//        exit;
        if ($test1){
            $token1=Cache::get($test1);
            var_dump($token1);
        }
        if ($test2){
            $token2=Cache::get($test2);
            var_dump($token2);
    
        }

    
    }
    public function test1(){
        $expire_in=config('weixin.expire_in');
        $model=new \app\weixin\service\Token();
        $token=$model->createToken();
        $value='test1';
        $cache=cache($token,$value,$expire_in);
        echo 'expire_in',$expire_in;
        if (!$cache){
            echo 'error';
        }
        else{
            echo 'ok';
            Cookie::set('test1',$token);
        }
    }
    public function test2(){
        $expire_in=config('weixin.expire_in');
        $model=new \app\weixin\service\Token();
        $token=$model->createToken();
        $value='test2';
        $cache=Cache::set($token,$value,$expire_in);
        echo 'expire_in',$expire_in;
        if (!$cache){
            echo 'error';
        }
        else{
            echo 'ok';
            Cookie::set('test2',$token);
        }
    }
    
}