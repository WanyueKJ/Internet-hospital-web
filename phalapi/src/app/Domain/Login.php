<?php

namespace App\Domain;

use App\ApiException;
use App\Model\User as Model_User;
use App\Model\Usertoken as Model_Usertoken;

class Login
{

  protected $fields = 'id,user_nickname,user_pass,avatar,avatar_thumb,sex,signature,balance,birthday,user_status,mobile,last_login_time';




  public function checkCode($type, $mobile, $code)
  {

    $rs = ['code' => 0, 'msg' => '', 'info' => []];

    if ($mobile == '') {
      $rs['code'] = 995;
      $rs['msg'] = \PhalApi\T('请输入手机号');
      return $rs;
    }

    if ($code == '') {
      $rs['code'] = 996;
      $rs['msg'] = \PhalApi\T('请输入验证码');
      return $rs;
    }

    $sms_key = 'sms_' . $type . '_' . $mobile;
    $sms_account = \App\getcaches($sms_key);

    if (!$sms_account) {
      $rs['code'] = 996;
      $rs['msg'] = \PhalApi\T('请先获取验证码');
      return $rs;
    }

    if ($sms_account['mobile'] != $mobile) {
      $rs['code'] = 995;
      $rs['msg'] = \PhalApi\T('手机号码错误');
      return $rs;
    }

    if ($sms_account['code'] != $code) {
      $rs['code'] = 996;
      $rs['msg'] = \PhalApi\T('验证码错误');
      return $rs;
    }

    if (time() > $sms_account['expiretime']) {
      $rs['code'] = 996;
      $rs['msg'] = \PhalApi\T('验证码已过期，请重新获取');
      return $rs;
    }

    return $rs;
  }

  public function clearCode($type, $mobile)
  {
    $sms_key = 'sms_' . $type . '_' . $mobile;
    \App\delcache($sms_key);
  }

  public function loginByPass($mobile, $pass)
  {

    $rs = ['code' => 0, 'msg' => '', 'info' => []];

    if ($mobile == '') {
      $rs['code'] = 995;
      $rs['msg'] = \PhalApi\T('请输入手机号');
      return $rs;
    }

    if ($pass == '') {
      $rs['code'] = 997;
      $rs['msg'] = \PhalApi\T('请输入密码');
      return $rs;
    }

    $where = [
      'mobile' => $mobile,
    ];

    $Model_User = new Model_User();
    $info = $Model_User->getInfo($where, $this->fields);

    if (!$info) {
      $rs['code'] = 1001;
      $rs['msg'] = \PhalApi\T('手机号尚未注册，请先注册');
      return $rs;
    }

    if (\App\setPass($pass) != $info['user_pass']) {
      $rs['code'] = 1001;
      $rs['msg'] = \PhalApi\T('密码有误');
      return $rs;
    }

    $info = $this->handleInfo($info);

    return $info;
  }

  public function loginByCode($mobile, $source)
  {

    $rs = ['code' => 0, 'msg' => '', 'info' => []];

    if ($mobile == '') {
      $rs['code'] = 995;
      $rs['msg'] = \PhalApi\T('请输入手机号');
      return $rs;
    }

    $where = [
      'mobile' => $mobile,
    ];

    $Model_User = new Model_User();
    $info = $Model_User->getInfo($where, $this->fields);
    $id = $info['id'] ?? 0;
    if (!$info) {
      /* 注册 */
      $nowtime = time();
      $user_login = 'phone_' . $nowtime . rand(100, 999);
      $nickname = \PhalApi\T('用户') . substr($mobile, -4);
      $data = array(
        'user_login' => $user_login,
        'user_nickname' => $nickname,
        "source" => $source,
        "mobile" => $mobile,
      );
      $id = $this->reg($data);
      if (!$id) {
        $rs = ['code' => 1001, 'msg' => \PhalApi\T('登录失败，请重试'), 'info' => []];
        return $rs;
      }
      $where2 = ['id' => $id];
      $info = $Model_User->getInfo($where2, $this->fields);
    }

    $info = $this->handleInfo($info);

    return $info;
  }


  public function reg($data = [])
  {

    $nowtime = time();
    $user_pass = 'edu' . $nowtime;
    $user_pass = \App\setPass($user_pass);

    $avatar = '/default.png';
    $avatar_thumb = '/default_thumb.png';

    $default = array(
      'user_pass' => $user_pass,
      'signature' => '',
      'avatar' => $avatar,
      'avatar_thumb' => $avatar_thumb,
      'last_login_ip' => \PhalApi\Tool::getClientIp(),
      'create_time' => $nowtime,
      'user_status' => 1,
    );

    if (isset($data['user_pass'])) {
      $data['user_pass'] = \App\setPass($data['user_pass']);
    }
    $insert = array_merge($default, $data);

    $Model_User = new Model_User();
    $rs = $Model_User->add($insert);
    if (!$rs) {
      return 0;
    }
    $id = $rs['id'];

    return $id;
  }

  public function regbypass($mobile, $pass, $source)
  {

    $rs = ['code' => 0, 'msg' => \PhalApi\T('注册成功'), 'info' => []];

    if ($mobile == '') {
      $rs['code'] = 995;
      $rs['msg'] = \PhalApi\T('请输入手机号');
      return $rs;
    }

    if ($pass == '') {
      $rs['code'] = 997;
      $rs['msg'] = \PhalApi\T('请输入密码');
      return $rs;
    }

    if (!\App\checkPass($pass)) {
      $rs['code'] = 997;
      $rs['msg'] = \PhalApi\T('密码为6-20位字母数字组合');
      return $rs;
    }

    $where = [
      'mobile' => $mobile,
    ];

    $Model_User = new Model_User();
    $isexist = $Model_User->getInfo($where, 'id');
    if ($isexist) {
      $rs['code'] = 1004;
      $rs['msg'] = \PhalApi\T('该手机号已注册，请登录');
      return $rs;
    }
    $nowtime = time();
    /* 注册 */
    $user_login = 'phone_' . $nowtime . rand(100, 999);
    $nickname = \PhalApi\T('用户') . substr($user_login, -4);
    $data = array(
      'user_login' => $user_login,
      'user_pass' => $pass,
      'user_nickname' => $nickname,
      'mobile' => $mobile,
      "source" => $source,
    );
    $id = $this->reg($data);

    $where2 = ['id' => $id];
    $info = $Model_User->getInfo($where2, $this->fields);

    $info = $this->handleInfo($info);

    return $info;
  }

  public function handleInfo($info)
  {

    $rs = ['code' => 0, 'msg' => \PhalApi\T('登录成功'), 'info' => []];
    if ($info['user_status'] == '0') {
      $rs['code'] = 1004;
      $rs['msg'] = \PhalApi\T('该账号已被禁用');
      return $rs;
    }

    unset($info['user_status']);
    unset($info['user_pass']);

    $info['isreg'] = '0';
    if (!$info['last_login_time']) {
      $info['isreg'] = '1';
    }
    unset($info['last_login_time']);

    $info = \App\handleUser($info);

    \App\delcache('userinfo_' . $info['id']);

    $token = $this->updateToken($info['id']);
    $info['token'] = $token;

    $info['im']['UserSig'] = \App\txim_setSig("users_{$info['id']}");
    $info['im']['userId'] = "users_{$info['id']}";
    $rs['info'][0] = $info;
    return $rs;
  }

  /* 更新token 登陆信息 */
  public function updateToken($uid)
  {

    $token = md5(md5($uid . time() . rand(1000, 9999)));

    $nowtime = time();
    $expiretime = $nowtime + 60 * 60 * 24 * 150;

    $Model_User = new Model_User();

    $where = ['id' => $uid];
    $up = [
      'last_login_time' => $nowtime,
      'last_login_ip' => \PhalApi\Tool::getClientIp(),
    ];
    $Model_User->up($where, $up);


    $token_info = array(
      'user_id' => $uid,
      'token' => $token,
      'expire_time' => $expiretime,
      'create_time' => $nowtime,
    );

    $Model_Usertoken = new Model_Usertoken();
    $where2 = ['user_id' => $uid];

    $isexist = $Model_Usertoken->up($where2, $token_info);
    if (!$isexist) {
      $Model_Usertoken->add($token_info);
    }

    \App\setcaches("token_" . $uid, $token_info);

    return $token;
  }

  public
  function forget($mobile, $pass)
  {
    $rs = ['code' => 0, 'msg' => \PhalApi\T('重置成功'), 'info' => []];

    if ($mobile == '') {
      $rs['code'] = 995;
      $rs['msg'] = \PhalApi\T('请输入手机号');
      return $rs;
    }

    if ($pass == '') {
      $rs['code'] = 997;
      $rs['msg'] = \PhalApi\T('请输入密码');
      return $rs;
    }

    if (!\App\checkPass($pass)) {
      $rs['code'] = 997;
      $rs['msg'] = \PhalApi\T('密码为6-20位字母数字组合');
      return $rs;
    }

    $where = [
      'mobile' => $mobile,
    ];

    $Model_User = new Model_User();
    $isexist = $Model_User->getInfo($where, 'id');
    if (!$isexist) {
      $rs['code'] = 1001;
      $rs['msg'] = \PhalApi\T('该手机号尚未注册');
      return $rs;
    }

    $pass = \App\setPass($pass);

    $up = ['user_pass' => $pass];
    $where = ['id' => $isexist['id']];

    $Model_User->up($where, $up);

    return $rs;
  }
}
