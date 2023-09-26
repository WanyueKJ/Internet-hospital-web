<?php

namespace App\Domain;

class Health
{

  public function More($page, $num, $type)
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());
    $where['type'] = $type;
    $list = (new \App\Model\Health)->list($where, $page, $num);
    $rs['info'] = $list;
    return $rs;
  }


  public function Collect($id, $uid)
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());
    $Health = new \App\Model\Health();
    $info = $Health->one(['id' => $id]);
    if (!$info) {
      $rs['code'] = 2001;
      $rs['msg'] = \PhalApi\T('收藏的内容不存在');
      return $rs;
    }
    $info = $Health->oneCollect(['health_id' => $id, 'uid' => $uid]);
    if ($info) {
      $rs['code'] = 2000;
      $rs['msg'] = \PhalApi\T('不能重复收藏');
      return $rs;
    }
    $info = $Health->addCollect(['uid' => $uid, 'health_id' => $id]);
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
    $Health = new \App\Model\Health();
    $info = $Health->deleteCollect(['uid' => $uid, 'health_id' => $id]);
    if (!$info) {
      $rs['code'] = 2001;
      $rs['msg'] = \PhalApi\T('信息有误');
      return $rs;
    }
    return $rs;
  }


  public function userCollect($uid, $type, $page, $num)
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());
    $Health = new \App\Model\Health();
    $info = $Health->userCollect(['A.uid' => $uid, 'B.type' => $type], '*', $page, $num);
    $rs['info'] = $info;
    return $rs;
  }


  public function details($id, $uid)
  {
    $Health = new \App\Model\Health();
    $info = $Health->one(['id' => $id]);
    if ($info) {
      $info['is_collect'] = $Health->oneCollect(['uid' => $uid, 'health_id' => $id]) ? 1 : 0;
      $info['content'] = html_entity_decode($info['content']);
      $info['addtime'] = date('Y-m-d H:i:s', $info['addtime']);
      unset($info['sort']);
      unset($info['uptime']);
      unset($info['image']);
    } else {
      $info = [];
    }
    return $info;
  }
}
