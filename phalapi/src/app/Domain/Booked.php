<?php

namespace App\Domain;

use App\Model\Doctor;

class Booked
{
  // 预约医生列表
  public function appointmentList()
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());
    $list = (new Doctor)->list([], 'id,img,name,content');
    foreach ($list as $k => $v) {
      $v['img'] =  \App\get_upload_path($v['img']);
      $list[$k] = $v;
    }
    $rs['info'] = $list;
    return $rs;
  }


  // 预约医生列表
  public function details($id)
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());
    $list = (new Doctor)->list(['id' => $id])[0] ?? '';
    if (!$list) {
      $rs['code'] = 2001;
      $rs['msg'] = '信息有误';
      return $rs;
    }
    $list['info'] = json_decode($list['info'], true);
    $list['img'] =  \App\get_upload_path($list['img']);
    unset($list['sort']);
    foreach ($list['info'] as $k2 => $v2) {
      $list['info'][$k2] = $v2['time_start'] . "~" . $v2['time_end'];
    }
    $list['notice'] = [
      '您可以在我的预约单查看订单详情。',
      '如果您预约成功,由于个人原因不能及时到店,请及时取消订单选择。',
      '详细的服务流程以店内为准。'
    ];
    $rs['info'] = $list;
    return $rs;
  }




  public function Collect($id, $uid)
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());
    $Doctor = new \App\Model\Doctor();
    $info = $Doctor->one(['id' => $id]);
    if (!$info) {
      $rs['code'] = 2001;
      $rs['msg'] = \PhalApi\T('关注的医生不存在');
      return $rs;
    }
    $info = $Doctor->oneCollect(['doctor_id' => $id, 'uid' => $uid]);
    if ($info) {
      $rs['code'] = 2000;
      $rs['msg'] = \PhalApi\T('不能重复关注');
      return $rs;
    }
    $info = $Doctor->addCollect(['uid' => $uid, 'doctor_id' => $id]);
    if (!$info) {
      $rs['code'] = 2001;
      $rs['msg'] = \PhalApi\T('信息有误');
      return $rs;
    }
    return $rs;
  }


  public function deleteCollect($id, $uid)
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());
    $Doctor = new \App\Model\Doctor();
    $info = $Doctor->deleteCollect(['uid' => $uid, 'doctor_id' => $id]);
    if (!$info) {
      $rs['code'] = 2001;
      $rs['msg'] = \PhalApi\T('信息有误');
      return $rs;
    }
    return $rs;
  }


  public function Collectlist($uid, $page, $num)
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());
    $Doctor = new \App\Model\Doctor();
    $info = $Doctor->Collectlist(['uid' => $uid], $page, $num);
    $rs['info'] = $info;
    return $rs;
  }


  public function inquiry()
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());
    $select = 'A.id,A.name,A.professional,A.img,A.message';
    $Doctor = new \App\Model\Doctor();
    $info = $Doctor->inquiry($select);
    $rs['info'] = $info;
    return $rs;
  }


  public function inquiryDetails($id, $uid)
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());
    $select = 'A.id,A.name,A.professional,A.img,A.message,A.content,A.online_cost';
    $where = ['A.id' => $id];
    $list = (new Doctor)->inquiryOne($select, $where);
    if (!$list) {
      $rs['code'] = 2001;
      $rs['msg'] = '信息有误';
      return $rs;
    }
    $list['cost'] =  $list['online_cost'];
    unset($list['online_cost']);
    $list['is_collect'] =  (new Doctor)->oneCollect(['uid' => $uid, 'doctor_id' => $id]) ? 1 : 0;
    $list['notice'] = [
      '医生可能正在门诊或手术，不能及时回复请您谅解。',
      '咨询时间为24小时，医生给出医学建议后，可主动结束订单。',
      '如医生未回复咨询，订单将在24小时后自动退款。',
      '问诊为医疗服务项目，一但建立医患关系，不允许退款，请谨慎。'
    ];
    $list['agreement'] = [
      ['host' => 'https://kyhospital.sdwanyue.com//appapi/page/detail?id=4', 'name' => '知情通知书'],
      ['host' => 'https://kyhospital.sdwanyue.com//appapi/page/detail?id=5', 'name' => '隐私条款'],
    ];
    $rs['info'] = $list;
    return $rs;
  }

  public function reservedlist($uid, $page, $num)
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());
    $select = 'B.name,A.money,A.servicetime,A.complete as status,A.userInfo';
    $where = [
      'A.uid' => $uid,
      'A.type' => 1,
      'A.status' => 2,
    ];
    $info  = (new \App\Model\Orders)->feacall($where, $select, $page, $num);
    foreach ($info as $k => $v) {
      $userInfo = json_decode($info[$k]['userInfo'], true);
      $info[$k]['user_name'] = $userInfo['name'];
      $info[$k]['phone'] = $userInfo['phone'];
      unset($info[$k]['userInfo']);
    }
    $rs['info'] = $info;
    return $rs;
  }

  public function inquirylist($uid, $page, $num, $status)
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());
    $select = 'A.remark,A.money,A.addtime,A.complete as status,A.id,A.doctor_id';
    $where = [
      'A.uid' => $uid,
      'A.type' => 2,
      'A.status' => 2,
      'A.complete' => $status
    ];

    $info  = (new \App\Model\Orders)->feacall($where, $select, $page, $num);

    $info = array_map(function ($v) {
      $v['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
      return $v;
    }, $info);

    $rs['info'] = $info;
    return $rs;
  }

  public function userinfo($uid, $oid)
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());
    $info = (new \App\Model\Orders)->userinfo(['id' => $oid], 'userInfo');
    $rs['info'] = json_decode($info['userInfo'], true);
    return $rs;
  }
}
