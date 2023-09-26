<?php

namespace App\Api;

use PhalApi\Api;

/**
 * 聊天地址
 */

class SocketUrl extends Api
{

  /**
   * 聊天地址
   *
   * @return string info[url] 地址
   */
  public function url()
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
    $url = \App\getConfigPri()['webscoket'];
    $rs['info']['url'] = $url;
    return $rs;
  }
}