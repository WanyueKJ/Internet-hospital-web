<?php

namespace App\Api;

use App\Model\Portalpost;
use PhalApi\Api;


/**
 * 协议获取
 */
class Agreement extends Api
{

  public function getRules()
  {
    return [
      'get' => [],
    ];
  }

  /**
   * 协议规则
   * @desc 个人中心协议规则
   * @return array info 协议信息
   * @return string msg 提示信息
   */
  public function get()
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());
    $uid = \App\checkNull($this->uid);
    $token = \App\checkNull($this->token);
    $checkToken = \App\checkToken($uid, $token);
    // if ($checkToken == 700) {
    //     $rs['code'] = $checkToken;
    //     $rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
    //     return $rs;
    // }
    $where = ['post_type' => 2, 'type' => 2, 'id < :id' => [":id" => 7]];
    $list = (new Portalpost)->list($where, 'id,post_title');
    foreach ($list as $k => $v) {
      $list[$k]['host'] = \App\get_host() . "/appapi/page/detail?id=" . $v['id'];
    }
    $rs['info'] = $list;
    return $rs;
  }
}
