<?php

namespace App\Api;

use PhalApi\Api;

/**
 * 健康-视频
 */
class Health extends Api
{
  public function getRules()
  {
    return [
      'healthMore' => [
        'page' => ['name' => 'page', 'type' => 'string', 'desc' => '第几页'],
        'num' => ['name' => 'num', 'type' => 'string', 'desc' => '取多少']
      ],
      'videoMore' => [
        'page' => ['name' => 'page', 'type' => 'string', 'desc' => '第几页'],
        'num' => ['name' => 'num', 'type' => 'string', 'desc' => '取多少']
      ],
      'Collect' => [
        'id' => ['name' => 'id', 'type' => 'string', 'desc' => '收藏id']
      ],
      'deleteCollect' => [
        'id' => ['name' => 'id', 'type' => 'string', 'desc' => '收藏id']
      ],
      'details' => [
        'id' => ['name' => 'id', 'type' => 'string', 'desc' => 'id']
      ],
      'userCollectH' => [
        'page' => ['name' => 'page', 'type' => 'string', 'desc' => '第几页'],
        'num' => ['name' => 'num', 'type' => 'string', 'desc' => '取多少']
      ],
      'UserCollectV' => [
        'page' => ['name' => 'page', 'type' => 'string', 'desc' => '第几页'],
        'num' => ['name' => 'num', 'type' => 'string', 'desc' => '取多少']
      ]
    ];
  }

  /**
   * 科普列表
   *
   * @return array info 科普列表信息
   */
  public function healthMore()
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
    $page = \App\checkNull($this->page);
    $num = \App\checkNull($this->num);
    $info = (new \App\Domain\Health)->More($page, $num, 1);
    return $info;
  }


  /**
   * 视频列表
   *
   * @return array info 视频列表信息
   */
  public function videoMore()
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
    $page = \App\checkNull($this->page);
    $num = \App\checkNull($this->num);
    $info = (new \App\Domain\Health)->More($page, $num, 2);
    return $info;
  }



  /**
   * 收藏
   *
   * @return string msg 提示信息
   * @return int code  0成功
   */
  public function Collect()
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
    $id = \App\checkNull($this->id);
    $info = (new \App\Domain\Health)->Collect($id, $uid);
    return $info;
  }


  /**
   * 取消收藏
   *
   * @return string msg 提示信息
   * @return int code  0成功
   */
  public function deleteCollect()
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
    $id = \App\checkNull($this->id);
    $info = (new \App\Domain\Health)->deleteCollect($id, $uid);
    return $info;
  }



  /**
   * 个人中心收藏-科普
   *
   * @return array info 科普收藏信息
   */
  public function userCollectH()
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
    $page = \App\checkNull($this->page);
    $num = \App\checkNull($this->num);
    $info = (new \App\Domain\Health)->userCollect($uid, 1, $page, $num);
    return $info;
  }


  /**
   * 个人中心收藏-视频
   *
   * @return array info 科普收藏信息
   */
  public function userCollectV()
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
    $page = \App\checkNull($this->page);
    $num = \App\checkNull($this->num);
    $info = (new \App\Domain\Health)->userCollect($uid, 2, $page, $num);
    return $info;
  }

  /**
   * 详情
   * @desc 视频-科普详情
   * @return array info 详情信息
   * @return string info.type 1科普 2视频
   * @return string info.addtime 发布时间
   * @return string info.name 标题
   * @return string info.content 内容（type2类型为视频链接）
   * @return string info.is_collect 是否收藏 0未收藏 1收藏
   */
  public function details()
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
    $id = \App\checkNull($this->id);
    $info = (new \App\Domain\Health)->details($id, $uid);
    return $info;
  }
}
