<?php

namespace App\Api;

use App\ApiException;
use PhalApi\Api;
use App\Domain\Login as Domain_Login;
use App\Domain\User as Domain_User;
use App\service\wechat\MiniProgramService;

/**
 * 注册、登录
 */
class Login extends Api
{
  public function getRules()
  {
    return array(

      'getCode' => array(
        'mobile' => array('name' => 'mobile', 'type' => 'string', 'desc' => '手机号码'),
        'type' => array('name' => 'type', 'type' => 'int', 'desc' => '类型，1登录 2注册 3忘记密码 5修改手机号'),
        'sign' => array('name' => 'sign', 'type' => 'string', 'default' => '', 'desc' => '签名 mobile type'),
        'code' => array('name' => 'code', 'type' => 'string', 'default' => '', 'desc' => '国家手机号区号'),
      ),

      'reg' => array(
        'mobile' => array('name' => 'mobile', 'type' => 'string', 'desc' => '手机号码'),
        'code' => array('name' => 'code', 'type' => 'string', 'desc' => '验证码'),
        'pass' => array('name' => 'pass', 'type' => 'string', 'desc' => '密码'),
        'pushid' => array('name' => 'pushid', 'type' => 'string', 'desc' => '极光推送ID'),
        'agentcode' => array('name' => 'agentcode', 'type' => 'string', 'desc' => '邀请码'),
      ),

      'loginByPass' => array(
        'mobile' => array('name' => 'mobile', 'type' => 'string', 'desc' => '手机号码'),
        'pass' => array('name' => 'pass', 'type' => 'string', 'desc' => '密码'),
        'pushid' => array('name' => 'pushid', 'type' => 'string', 'desc' => '极光推送ID'),
      ),

      'loginByCode' => array(
        'mobile' => array('name' => 'mobile', 'type' => 'string', 'desc' => '手机号码'),
        'code' => array('name' => 'code', 'type' => 'string', 'desc' => '验证码'),
        'openid' => array('name' => 'openid', 'type' => 'string', 'desc' => '第三方openid(微信类传openid)'),
        'pushid' => array('name' => 'pushid', 'type' => 'string', 'desc' => '极光推送ID'),
        'agentcode' => array('name' => 'agentcode', 'type' => 'string', 'desc' => '邀请码'),
      ),


      'getMerUnionid' => array(
        'code' => array('name' => 'code', 'type' => 'string', 'desc' => '微信登录code'),
      ),

      'forget' => array(
        'mobile' => array('name' => 'mobile', 'type' => 'string', 'desc' => '手机号码'),
        'code' => array('name' => 'code', 'type' => 'string', 'desc' => '验证码'),
        'pass' => array('name' => 'pass', 'type' => 'string', 'desc' => '密码'),
        'passs' => array('name' => 'passs', 'type' => 'string', 'desc' => '确认密码'),
      ),
    );
  }


  /**
   * 获取验证码
   * @desc 用于注册验证码
   * @return int code 操作码，0表示成功,2发送失败
   * @return array info
   * @return string msg 提示信息
   */
  public function getCode()
  {
    $rs = array('code' => 0, 'msg' => \PhalApi\T('发送成功，请注意查收'), 'info' => array());

    $mobile = \App\checkNull($this->mobile);
    $type = \App\checkNull($this->type);
    $sign = \App\checkNull($this->sign);

    // $action = 'App.Login.getCode';
    // $date = date('Y-m-d H:i:s');
    // file_put_contents('./log.txt', var_export(compact('action','date','mobile','type', 'country_code'), true) . PHP_EOL, FILE_APPEND);


    $checkdata = array(
      'mobile' => $mobile,
      'type' => $type,
    );

    $issign = \App\checkSign($checkdata, $sign);
    //        if (!$issign) {
    //            $rs['code'] = 1001;
    //            $rs['msg'] = \PhalApi\T('签名错误');
    //            return $rs;
    //        }
    $types = [1, 2, 3, 5]; //4已经被用过了
    if (!in_array($type, $types)) {
      $rs['code'] = 1000;
      $rs['msg'] = \PhalApi\T('信息错误');
      return $rs;
    }

    if ($mobile == '') {
      $rs['code'] = 995;
      $rs['msg'] = \PhalApi\T('请输入手机号');
      return $rs;
    }

    $isok = \App\checkMobile($mobile);
    if (!$isok) {
      $rs['code'] = 995;
      $rs['msg'] = \PhalApi\T('请输入正确的手机号');
      return $rs;
    }


    $where = ['mobile' => $mobile];
    $Domain_User = new Domain_User();

    $isexist = $Domain_User->getInfo($where, 'id');
    if ($type == 1) {
      if (!$isexist) {
        $rs['code'] = 1004;
        $rs['msg']  = \PhalApi\T('该手机号尚未注册，请先注册');
        return $rs;
      }
    }

    if ($type == 2) {
      /* 注册 */
      if ($isexist) {
        $rs['code'] = 1002;
        $rs['msg'] = \PhalApi\T('该手机号已注册，请登录');
        return $rs;
      }
    }

    if ($type == 3) {
      /* 忘记密码 */
      if (!$isexist) {
        $rs['code'] = 1003;
        $rs['msg'] = \PhalApi\T('该手机号尚未注册，请先注册');
        return $rs;
      }
    }

    if ($type == 5) {
      /* 修改手机号 */
      if ($isexist) {
        $rs['code'] = 1002;
        $rs['msg'] = \PhalApi\T('该手机号已注册');
        return $rs;
      }
    }

    //取redis验证码
    $sms_key = 'sms_' . $type . '_' . $mobile;
    $sms_account = \App\getcaches($sms_key);

    if ($sms_account && $sms_account['expiretime'] > time()) {
      $rs['code'] = 996;
      $rs['msg'] = \PhalApi\T('验证码5分钟有效，请勿多次发送');
      return $rs;
    }

    $code = \App\random(6);

    /* 发送验证码 */
    $result = \App\sendCode($mobile, $code);
    $currentTime = time();

    $sms_code = '';
    if ($result['code'] == 0) {
      $sms_code = $code;
    } else if ($result['code'] == 667) {
      $sms_code = $result['msg'];
      $rs['code'] = 1002;
      $rs['msg'] = \PhalApi\T('验证码为：{n}', ['n' => $result['msg']]);
    } else {
      $rs['code'] = 1002;
      $rs['msg'] = $result['msg'];
    }

    if ($sms_code) {
      $sms_value = [
        'mobile' => $mobile,
        'code' => $sms_code,
        'expiretime' => $currentTime + 300, //超时5分钟
      ];
      \App\setcaches($sms_key, $sms_value, 300);
    }

    return $rs;
  }

  /**
   * 手机号注册
   * @desc 用于用户通过手机号注册
   * @return int code 操作码，0表示成功
   * @return array info 用户信息
   * @return string info[0].id 用户ID
   * @return string msg 提示信息
   */
  public function reg()
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());

    $mobile = \App\checkNull($this->mobile);
    $pass = \App\checkNull($this->pass);
    $code = \App\checkNull($this->code);
    $source = \App\checkNull($this->source);
    $pushid = \App\checkNull($this->pushid);
    $agentcode = \App\checkNull($this->agentcode);

    $domain = new Domain_Login();

    $res = $domain->checkCode(2, $mobile, $code);
    if ($res['code'] != 0) {
      return $res;
    }

    $info = $domain->regbypass($mobile, $pass, $source, $pushid, $agentcode);

    if ($info['code'] == 0) {
      $domain->clearCode(2, $mobile);
    }

    return $info;
  }

  /**
   * 密码登录
   * @desc 用于用户通过密码登录
   * @return int code 操作码，0表示成功
   * @return array info 用户信息
   * @return string info[0].id 用户ID
   * @return string info[0].user_nickname 昵称
   * @return string info[0].avatar 头像
   * @return string info[0].avatar_thumb 头像缩略图
   * @return string info[0].sex 性别
   * @return string info[0].signature 签名
   * @return string info[0].coin 用户余额
   * @return string info[0].login_type 注册类型
   * @return string info[0].token 用户Token
   * @return string msg 提示信息
   */
  public function loginByPass()
  {

    $mobile = \App\checkNull($this->mobile);
    $pass = \App\checkNull($this->pass);
    $pushid = \App\checkNull($this->pushid);

    $domain = new Domain_Login();
    $info = $domain->loginByPass($mobile, $pass, $pushid);

    return $info;
  }

  /**
   * 验证码登录
   * @desc 用于用户通过验证码登录
   * @return int code 操作码，0表示成功
   * @return array info 用户信息
   * @return string info[0].id 用户ID
   * @return string info[0].im IM信息
   * @return string info[0].im.userId  userId
   * @return string info[0].im.UserSig
   * @return string msg 提示信息
   */
  public function loginByCode()
  {

    $mobile = \App\checkNull($this->mobile);
    $code = \App\checkNull($this->code);
    $source = \App\checkNull($this->source);
    $openid = \App\checkNull($this->openid);
    $pushid = \App\checkNull($this->pushid);
    $agentcode = \App\checkNull($this->agentcode);
    if ($source == 3 && !$openid) {
      throw new ApiException(\PhalApi\T('参数信息有误:openid'));
    }

    $domain = new Domain_Login();

    $res = $domain->checkCode(1, $mobile, $code);
    if ($res['code'] != 0) {
      return $res;
    }
    $info = $domain->loginByCode($mobile, $source, $pushid, $agentcode, $openid);

    if ($info['code'] == 0) {
      $domain->clearCode(1, $mobile);
    }

    return $info;
  }



  /**
   * 忘记密码
   * @desc 用于用户忘记密码时重置密码
   * @return int code 操作码，0表示成功
   * @return array info 用户信息
   * @return string msg 提示信息
   */
  public function forget()
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());

    $mobile = \App\checkNull($this->mobile);
    $pass = \App\checkNull($this->pass);
    $passs = \App\checkNull($this->passs);
    $code = \App\checkNull($this->code);

    if ($pass != $passs) {
      $rs['meg'] = '密码不一致';
      $rs['code'] = 2001;
      return $rs;
    }
    $domain = new Domain_Login();

    $res = $domain->checkCode(3, $mobile, $code);
    if ($res['code'] != 0) {
      return $res;
    }

    $info = $domain->forget($mobile, $pass);

    if ($info['code'] == 0) {
      $domain->clearCode(3, $mobile);
    }

    return $info;
  }
}
