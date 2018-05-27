<?php
/**
 * Created by PhpStorm.
 * User: hkb
 * Date: 2018/4/14
 * Time: 8:21
 */

namespace app\weixin\service;


use think\Controller;

class Base extends Controller
{
    protected $url;
    protected $code='';
    protected $access_token;
    protected $appId;
    protected $appSecret;
    //授权后重定向的地址
   protected $redirect_uri;
   
}