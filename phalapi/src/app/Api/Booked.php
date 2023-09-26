<?php

namespace App\Api;

use PhalApi\Api;

/**
 * 预约-问诊
 */
class Booked extends Api
{
  public function getRules()
  {
    return [
      'appointmentList' => [],
      'details' => [
        'id' => ['name' => 'id', 'type' => 'string', 'desc' => '医生id', 'require' => true]
      ],
      'Collect' => [
        'id' => ['name' => 'id', 'type' => 'string', 'desc' => '医生id']
      ],
      'deleteCollect' => [
        'id' => ['name' => 'id', 'type' => 'string', 'desc' => '医生id']
      ],
      'InquiryDetails' => [
        'id' => ['name' => 'id', 'type' => 'string', 'desc' => '医生id', 'require' => true]
      ],
      'Collectlist' => [
        'page' => ['name' => 'page', 'type' => 'string', 'desc' => '第几页'],
        'num' => ['name' => 'num', 'type' => 'string', 'desc' => '取多少']
      ],
      'Reservedlist' => [
        'page' => ['name' => 'page', 'type' => 'string', 'desc' => '第几页'],
        'num' => ['name' => 'num', 'type' => 'string', 'desc' => '取多少']
      ],
      'inquirylist' => [
        'status' => ['name' => 'status', 'type' => 'string', 'require' => true, 'desc' => '0未完成 1已完成'],
        'page' => ['name' => 'page', 'type' => 'string', 'desc' => '第几页'],
        'num' => ['name' => 'num', 'type' => 'string', 'desc' => '取多少']
      ],
      'userinfo' => [
        'oid' => ['name' => 'oid', 'type' => 'string', 'desc' => '订单id', 'require' => true]
      ],

    ];
  }

  /**
   * 预约医生列表
   * @desc 医生列表
   * @return array info 预约医生列表
   * @return string info[0].name 名称
   * @return string info[0].img 头像
   * @return string info[0].content 介绍
   */
  public function appointmentList()
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
    $list = (new \App\Domain\Booked)->appointmentList();
    return $list;
  }


  /**
   * 预约医生详情
   * @desc 预约医生
   * @return array info 医生详情
   * @return string info.name 名称
   * @return string info.professional 职称
   * @return string info.img 头像
   * @return string info.content 介绍
   * @return string info.message 擅长
   * @return string info.cost 价格
   * @return array info.info 时间节点
   * @return array info.notice 服务须知
   */
  public function details()
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());
    $uid = \App\checkNull($this->uid);
    $id = \App\checkNull($this->id);
    $token = \App\checkNull($this->token);
    $checkToken = \App\checkToken($uid, $token);
    if ($checkToken == 700) {
      $rs['code'] = $checkToken;
      $rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
      return $rs;
    }
    $list = (new \App\Domain\Booked)->details($id);
    return $list;
  }



  /**
   * 关注
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
    $info = (new \App\Domain\Booked)->Collect($id, $uid);
    return $info;
  }



  /**
   * 取消关注
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
    $info = (new \App\Domain\Booked)->deleteCollect($id, $uid);
    return $info;
  }



  /**
   * 关注列表
   *
   * @return string msg 提示信息
   * @return int code  0成功
   */
  public function Collectlist()
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
    $info = (new \App\Domain\Booked)->Collectlist($uid, $page, $num);
    return $info;
  }

  /**
   * 问诊医生列表
   *
   * @return string info[0].id 医生id
   * @return string info[0].name 医生名称
   * @return string info[0].professional 医生职称
   * @return string info[0].img 医生头像
   * @return string info[0].signboard 医生标识标签
   */
  public function inquiry()
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
    $info = (new \App\Domain\Booked)->inquiry($uid);
    return $info;
  }



  /**
   * 问诊医生详情
   * @desc 问诊医生
   * @return array info 医生详情
   * @return string info.name 名称
   * @return string info.professional 职称
   * @return string info.img 头像
   * @return string info.content 介绍
   * @return string info.message 擅长
   * @return string info.cost 价格
   * @return array info.notice 服务须知
   * @return array info.is_collect 是否关注 0未关注 1关注
   * @return array info.agreement[] 协议
   */
  public function inquiryDetails()
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());
    $uid = \App\checkNull($this->uid);
    $id = \App\checkNull($this->id);
    $token = \App\checkNull($this->token);
    $checkToken = \App\checkToken($uid, $token);
    if ($checkToken == 700) {
      $rs['code'] = $checkToken;
      $rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
      return $rs;
    }
    $list = (new \App\Domain\Booked)->inquiryDetails($id, $uid);
    return $list;
  }


  /**
   * 预约记录
   *
   * @return array info 预约记录列表
   * @return string info[0].name 医生名
   * @return string info[0].money 金额·
   * @return string info[0].status 0未完成 1完成
   * @return string info[0].user_name 预约人名字
   * @return string info[0].phone 预约手机
   * @return string info[0].servicetime 预约时间
   */
  public function  reservedlist()
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
    $list = (new \App\Domain\Booked)->reservedlist($uid, $page, $num);
    return $list;
  }


  /**
   * 问诊记录
   *
   * @return array info 问诊记录列表
   * @return string info[0].remark 备注
   * @return string info[0].money 金额·
   * @return string info[0].status 0未完成 1完成
   * @return string info[0].addtime 下单时间
   * @return string info[0].id 订单id聊天对话框用到
   * @return string info[0].doctor_id 医生id
   */
  public function  inquirylist()
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
    $status = \App\checkNull($this->status);
    $list = (new \App\Domain\Booked)->inquirylist($uid, $page, $num, $status);
    return $list;
  }

  /**
   * 病人信息
   *
   * @return array info 问诊人信息

   */
  public function userinfo()
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
    $oid = \App\checkNull($this->oid);
    $list = (new \App\Domain\Booked)->userinfo($uid, $oid);
    return $list;
  }
}
