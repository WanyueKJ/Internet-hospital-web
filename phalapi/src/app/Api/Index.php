<?php

namespace App\Api;

use PhalApi\Api;


/**
 * 首页
 */
class Index extends Api
{

  /**
   * 首页数据
   * @desc 首页信息
   * @return array info.harousel 轮播
   * @return array info.health 科普
   * @return array info.video 视频
   * @return array info.href[] 联系客服 ， 关于我们 , 专业版 , 家庭医生
   */
  public function  index()
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
    $list = (new \App\Domain\Index)->index();
    return $list;
  }
}
