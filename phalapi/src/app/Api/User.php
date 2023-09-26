<?php

namespace App\Api;

use App\ApiException;
use App\Domain\Login as Domain_Login;
use PhalApi\Api;
use App\Domain\User as Domain_User;
use App\Domain\Message as Domain_Message;

/**
 * 用户信息
 */
class User extends Api
{
  public function getRules()
  {
    return array(
      'getBaseInfo' => array(
        'ios_version' => array('name' => 'ios_version', 'type' => 'string', 'default' => '', 'desc' => 'IOS版本号'),
      ),

      'upUserInfo' => array(
        'fields' => array('name' => 'fields', 'type' => 'string', 'default' => '', 'desc' => '修改信息json串'),
      ),

      'upPass' => array(
        'oldpass' => array('name' => 'oldpass', 'type' => 'string', 'default' => '', 'desc' => '旧密码'),
        'newpass' => array('name' => 'newpass', 'type' => 'string', 'default' => '', 'desc' => '新密码'),
      ),
      'upMobile' => array(
        'code' => array('name' => 'code', 'type' => 'string', 'default' => '', 'desc' => '验证码(验证码用:App.Login.LoginByCode)'),
        'new_mobile' => array('name' => 'new_mobile', 'type' => 'string', 'default' => '', 'desc' => '新手机号'),
      ),



      'writeOff' => array(),
      'logOut' => array(),
    );
  }

  /**
   * 账号退出
   * @desc 账号退出
   * @return array
   * @throws ApiException
   */
  public function logOut()
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());

    $uid = \App\checkNull($this->uid);
    $token = \App\checkNull($this->token);
    $this->checkLogin($uid, $token);
    $DomainUser = new Domain_User();
    $DomainUser->logOut($uid);
    return $rs;
  }


  /**
   * 账号注销
   * @desc 账号注销
   * @return array
   * @throws ApiException
   */
  public function writeOff()
  {
    $uid = \App\checkNull($this->uid);
    $token = \App\checkNull($this->token);

    $this->checkLogin($uid, $token);
    $DomainUser = new Domain_User();
    $res = $DomainUser->setWriteOff($uid);
    return $res;
  }

  /**
   * 检测登录状态
   * @param $uid
   * @param $token
   * @return void
   * @throws ApiException
   */
  protected function checkLogin($uid, $token)
  {
    $checkToken = \App\checkToken($uid, $token);
    if ($checkToken == 700) {
      throw new ApiException(\PhalApi\T('您的登陆状态失效，请重新登陆！'), 700);
    }
  }

  /**
   * 修改手机号
   * @desc 修改手机号
   * @return array
   * @throws ApiException
   */
  public function upMobile()
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());
    $uid = \App\checkNull($this->uid);

    $token = \App\checkNull($this->token);
    $this->checkLogin($uid, $token);

    $code = \App\checkNull($this->code);
    $new_mobile = \App\checkNull($this->new_mobile);



    $domain = new Domain_Login();
    $res = $domain->checkCode(5, $new_mobile, $code);
    if ($res['code'] != 0) {
      return $res;
    }
    $domain = new Domain_User();
    $domain->upMobile($uid, $new_mobile);
    return $rs;
  }


  /**
   * 判断token
   * @desc 用于判断token
   * @return int code 操作码，0表示成功
   * @return array info
   * @return string msg 提示信息
   */
  public function iftoken()
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());

    $uid = \App\checkNull($this->uid);
    $token = \App\checkNull($this->token);

    $checkToken = \App\checkToken($uid, $token);
    if ($checkToken == 700) {
      $rs['code'] = $checkToken;
      $rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
      return $rs;
    }
    return $rs;
  }

  /**
   * 获取用户信息
   * @desc 用于获取单个用户基本信息
   * @return int code 操作码，0表示成功， 1表示用户不存在
   * @return array info
   * @return array info[0] 用户信息
   * @return int info[0]['id'] 用户ID
   * @return int info[0]['text_count'] 科普收藏数量
   * @return int info[0]['video_count'] 视频收藏数量
   * @return int info[0]['doctor_count'] 医生关注数量
   * @return array  info[0].list 下部列表
   * @return string msg 提示信息
   */
  public function getBaseInfo()
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());

    $uid = \App\checkNull($this->uid);
    $token = \App\checkNull($this->token);
    $checkToken = \App\checkToken($uid, $token);
    if ($checkToken == 700) {
      $rs['code'] = $checkToken;
      $rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
      return $rs;
    }

    $domain = new Domain_User();
    $info = $domain->getBaseInfo($uid);
    $Health = new \App\Model\Health();
    $text_count = count($Health->GetAll(['A.uid' => $uid, 'B.type' => 1]));
    $video_count = count($Health->GetAll(['A.uid' => $uid, 'B.type' => 2]));
    $doctor_count = count((new \App\Model\Doctor)->GetAll(['A.uid' => $uid]));
    if (!$info) {
      $rs['code'] = 700;
      $rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
      return $rs;
    }

    $img_time = '?t=1640153141';

    // $one = [
    //     ['id' => '1', 'name' => \PhalApi\T('我的挂号'), 'thumb' => \App\get_upload_path("/static/app/person2/hongbao.png") . $img_time, 'href' => '', 'nums' => '0', 'status' => '1'],
    //     ['id' => '2', 'name' => \PhalApi\T('我的问诊'), 'thumb' => \App\get_upload_path("/static/app/person2/zuJi.png") . $img_time, 'href' => '', 'nums' => '0', 'status' => '1'],
    // ];
    $two = [
      ['id' => '1', 'name' => \PhalApi\T('关于我们'), 'thumb' => \App\get_upload_path("/static/app/person2/About.png") . $img_time, 'href' => \App\get_host() . "/appapi/page/detail?id=7", 'nums' => '0', 'status' => '1'],
      ['id' => '2', 'name' => \PhalApi\T('资质证明'), 'thumb' => \App\get_upload_path("/static/app/person2/qualification.png") . $img_time, 'href' => \App\get_host() . "/appapi/page/detail?id=8", 'nums' => '0', 'status' => '1'],
      ['id' => '3', 'name' => \PhalApi\T('协议规则'), 'thumb' => \App\get_upload_path("/static/app/person2/deal.png") . $img_time, 'href' => \App\get_host() . '/api/?service=App.Agreement.Get', 'nums' => '0', 'status' => '1'],
      ['id' => '4', 'name' => \PhalApi\T('商用授权'), 'thumb' => \App\get_upload_path("/static/app/person2/warranty.png") . $img_time, 'href' => \App\get_host() . "/appapi/page/detail?id=12", 'nums' => '0', 'status' => '1'],
      ['id' => '5', 'name' => \PhalApi\T('咨询专业版'), 'thumb' => \App\get_upload_path("/static/app/person2/message.png") . $img_time, 'href' => \App\get_host() . "/appapi/page/detail?id=11", 'nums' => '0', 'status' => '1'],
    ];

    $info['text_count'] = $text_count;
    $info['video_count'] = $video_count;
    $info['doctor_count'] = $doctor_count;
    // $info['one'] = $one;
    $info['two'] = $two;
    $rs['info'][0] = $info;

    return $rs;
  }


  /**
   * 更新基本信息
   * @desc 用于用户更新基本信息
   * @return int code 操作码，0表示成功
   * @return array info
   * @return string msg 提示信息
   */
  public function upUserInfo()
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());

    $uid = \App\checkNull($this->uid);
    $token = \App\checkNull($this->token);
    $fields = $this->fields;

    if ($fields == '') {
      $rs['code'] = 1001;
      $rs['msg'] = \PhalApi\T('信息错误');
      return $rs;
    }
    //        file_put_contents('./log.txt', var_export(compact('uid', 'token', 'fields'), true) . PHP_EOL, FILE_APPEND);

    $fields_a = json_decode($fields, true);
    if (!$fields_a) {
      $rs['code'] = 1002;
      $rs['msg'] = \PhalApi\T('信息错误');
      return $rs;
    }

    $checkToken = \App\checkToken($uid, $token);
    if ($checkToken == 700) {
      $rs['code'] = $checkToken;
      $rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
      return $rs;
    }

    $domain = new Domain_User();
    $info = $domain->upUserInfo($uid, $fields_a);

    return $info;
  }

  /**
   * 更新密码
   * @desc 更新密码
   * @return int code 操作码，0表示成功
   * @return array info
   * @return string msg 提示信息
   */
  public function upPass()
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());

    $uid = \App\checkNull($this->uid);
    $token = \App\checkNull($this->token);
    $oldpass = \App\checkNull($this->oldpass);
    $newpass = \App\checkNull($this->newpass);

    $checkToken = \App\checkToken($uid, $token);
    if ($checkToken == 700) {
      $rs['code'] = $checkToken;
      $rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
      return $rs;
    }

    $domain = new Domain_User();
    $info = $domain->upPass($uid, $oldpass, $newpass);

    return $info;
  }
}
