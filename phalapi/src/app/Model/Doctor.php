<?php

namespace App\Model;

use PhalApi\Model\NotORMModel;

class Doctor extends NotORMModel
{
  public function list($where = [], $select = '*')
  {
    $list = \PhalApi\DI()->notorm->doctor
      ->select($select)->where($where)->fetchAll();
    return $list;
  }



  public function one($where = [])
  {
    $list = \PhalApi\DI()->notorm->doctor
      ->where($where)->fetchOne();
    return $list;
  }

  public function oneCollect($where = [])
  {
    $list = \PhalApi\DI()->notorm->doctor_collect
      ->where($where)->fetchOne();
    return $list;
  }


  public function addCollect($insert)
  {
    $list = \PhalApi\DI()->notorm->doctor_collect
      ->insert($insert);
    return $list;
  }


  public function deleteCollect($where = [])
  {
    $list = \PhalApi\DI()->notorm->doctor_collect
      ->where($where)->delete();
    return $list;
  }


  public function Collectlist($where = [], $page, $num)
  {
    $list = \PhalApi\DI()->notorm->doctor_collect
      ->select('B.id,B.name,B.img,B.professional,B.message')
      ->alias('A')->leftJoin('doctor', 'B', 'A.doctor_id = B.id')
      ->where($where)->page($page, $num)->fetchAll();
    if ($list) {
      foreach ($list as $k => $v) {
        $v['img'] = \App\get_upload_path($v['img']);
        $list[$k] = $v;
      }
    }
    return $list;
  }

  public function GetAll($where = [])
  {
    $list = \PhalApi\DI()->notorm->doctor_collect
      ->select('B.id,B.name,B.img,B.professional,B.message')
      ->alias('A')->leftJoin('doctor', 'B', 'A.doctor_id = B.id')
      ->where($where)->fetchAll();
    if ($list) {
      foreach ($list as $k => $v) {
        $v['img'] = \App\get_upload_path($v['img']);
        $list[$k] = $v;
      }
    }
    return $list;
  }
  public function inquiry($select = 'A.*')
  {
    $select = $select . ',B.name as signboard';
    $list = \PhalApi\DI()->notorm->doctor
      ->select($select)
      ->alias('A')->leftJoin('signboard', 'B', 'A.signboard_id = B.id')
      ->where('status', 1)->fetchAll();
    if (isset($list[0]['img'])) {
      foreach ($list as $k => $v) {
        $v['img'] = \App\get_upload_path($v['img']);
        $list[$k] = $v;
      }
    }
    return $list;
  }

  public function inquiryOne($select = 'A.*', $where)
  {
    $select = $select . ',B.name as signboard';
    $list = \PhalApi\DI()->notorm->doctor
      ->select($select)
      ->alias('A')->leftJoin('signboard', 'B', 'A.signboard_id = B.id')
      ->where('status', 1)
      ->where($where)
      ->fetchOne();
    if (isset($list['img'])) {
      $list['img'] = \App\get_upload_path($list['img']);
    }
    return $list;
  }
}
