<?php
/**
 * Created by PhpStorm.
 * User: hkb
 * Date: 2018/4/14
 * Time: 8:10
 */

namespace app\weixin\controller;

use app\lib\exception\ParamsException;
use app\weixin\service\Token as tokenModel;
use think\facade\Cache;
use think\facade\Cookie;


class Token extends Base
{
//    protected $beforeActionList=[
//        'checkToken'=>['only'=>'getToken']
//    ];
//    public function checkToken(){
//
//    }
    public function getToken()
    {
        $code = request()->get('code');
        $tokenModel = new tokenModel();
        $token = Cookie::get('token');
        //token对应的缓存值是否还在
        //如果不存在 则当做token也不存在
        //tp5.1当缓存过期时有时候不会自动删除缓存文件 需要再一次访问 当过期的时候才会删除
        //所以可以将cookie的过期时间设置得比缓存过期时间长一些 这样当使用cookie查找缓存的时候就会将过期的缓存文件删除
        //这里将cookie永久保存 重新生成token的时候在更新
        //更新之前将cookie 的值删除
        //这步操作==》获取缓存的时候 如果过期  缓存文件将被删除
        $tokenValue=Cache::get($token);
        if (!$tokenValue){
            Cookie::delete('token');
            $token='';
        }
        //当token的值不存在 并且code不存在的时候 要重定向获取code
        //重定向的时候：当code存在 token的时候
        //否则直接从缓存中获取用户信息
        if (empty($code) && empty($token)) {
            $tokenModel->getCode();
        } elseif (empty($token)) {
            //根据code换取access_token和用户信息
            //$userInfo=$tokenModel->getInfo($code);
            $token = $tokenModel->getToken($code);
        }
        //接口开发直接返回token
        //return $token;
        //网页开发 相当于登陆成功
        //跳转相应页面
        if ($token){
            //echo 'yeah';
            //$this->redirect('loginSuccess');
            $this->loginSuccess();
        }else{
            throw new ParamsException([
                'msg'=>'登陆失败，请返回重新登陆',
            ]);
        }
    }
    public function loginSuccess(){
        $token = Cookie::get('token');
        $tokenValue=json_decode(Cache::get($token));
        $time=date('Y-m-d H:i:s',$tokenValue->addtime);
        $outtime=date('Y-m-d H:i:s',$tokenValue->addtime+$tokenValue->expire_in);
        //登陆成功
        $html=<<<HTML
        <p>hello <b>$tokenValue->nickname</b></p><br>
        <img src="$tokenValue->headimgurl" alt=""><br>
        登陆时间：$time  <br>
        过期时间:$outtime  <br>
HTML;
        echo $html;
        echo  '登陆成功';exit;
    }
}