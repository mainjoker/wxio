<?php
/**
 * Created by PhpStorm.
 * User: hkb
 * Date: 2018/4/14
 * Time: 8:20
 */

namespace app\weixin\service;


use app\lib\exception\CacheException;
use app\lib\exception\ParamsException;
use app\weixin\model\user as userModel;
use think\facade\Cookie;

class Token extends Base
{
    public function __construct()
    {
        $this->appId = config('weixin.appId');
        $this->appSecret = config('weixin.appSecret');
    }
    
    public function getCode()
    {
        $this->redirect_uri = urlencode(config('weixin.baseUrl') . request()->url());
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
        $this->url = sprintf($url, $this->appId, $this->redirect_uri);
        $this->redirect($this->url);
    }
    
    //获取用户信息
    public function getToken($code)
    {
        $accessToken = $this->getAccessToken($code);
        $userInfo = $this->getUserinfo($accessToken);
        //将用户信息存储在token缓存中
        $token=$this->createToken();
        $expire_in=config('weixin.expire_in');
        $userInfo['expire_in']=$expire_in;
        $userInfo['addtime']=time();
        $userInfo=json_encode($userInfo);
        //$tokenCache=Cache::set($token,json_encode($userInfo),$expire_in);
        //$tokenCache=Cache::set($token,$userInfo,$expire_in);
        $tokenCache=cache("$token","$userInfo","$expire_in");
        //var_dump('tokenCache'.$tokenCache);
        if (!$tokenCache){
            throw new CacheException();
        }
        //将token的值存入cookie
        Cookie::set('token',$token);
        return $token;
    }
    public function createToken(){
        $salt=config('weixin.salt');
        $time=time();
        //随机字符串
        $str=createNonceStr(32);
        return md5($str.$salt.$time);
    }
    
    public function getAccessToken($code)
    {
        $appid = $this->appId;
        $appSecret = $this->appSecret;
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $appSecret . "&code=" . $code . "&grant_type=authorization_code";
        $res = json_decode(curl_get($url), true);
        if (array_key_exists('errcode', $res)) {
            //获取token失败
            throw new ParamsException([ 'msg' => $res['errmsg'], 'errCode' => $res['errcode'], ]);
        }
        return $res;
    }
    
    public function getUserinfo($res)
    {
        $accessToken = $res['access_token'];
        $openid=$res['openid'];
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$accessToken.'&openid='.$openid.'&lang=zh_CN';
        $userInfo = json_decode(curl_get($url), true);
        if (array_key_exists('errcode', $userInfo)) {
            throw new ParamsException([ 'msg' => $userInfo['errmsg'], 'errcode' => $userInfo['errcode'], ]);
        }
        //查看此用户是否已经存在 否则新增用户
        $uid=$this->checkUser($userInfo);
        $userInfo['uid']=$uid;
        return $userInfo;
    }
    //检查是否存在该用户 没有的话则新增
    public function checkUser($userInfo){
        $openid=$userInfo['openid'];
        $user=userModel::getUserByOpenid($openid);
        if(!$user){
            //新增用户
            $data=[];
            $data['openid']=$openid;
            $data['nickname']=$userInfo['nickname'];
            $data['create_time']=time();
            $user=userModel::create($data);
            $uid=$user->id;//主键id
        }
        $uid=$user->id;
        return $uid;
    }
}