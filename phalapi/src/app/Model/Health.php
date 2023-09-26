<?php

namespace App\Model;

use PhalApi\Model\NotORMModel;

class Health extends NotORMModel
{
  public function list($where = [], $page, $num)
  {
    $list = \PhalApi\DI()->notorm->health
      ->select('id,type,name,image,content')
      ->where($where)
      ->page($page, $num)->fetchAll();
    foreach ($list as $k => $v) {
      if ($v['type'] == 2) {
        $v['content'] = \App\get_upload_path($v['content']);
      }
      $v['image'] = \App\get_upload_path($v['image']);
      unset($v['type']);
      $list[$k] = $v;
    }
    return $list;
  }


  public function one($where = [])
  {
    $list = \PhalApi\DI()->notorm->health
      ->where($where)->fetchOne();
    if ($list) {
      if ($list['type'] == 2) {
        $list['content'] = \App\get_upload_path($list['content']);
      }
      $list['image'] = \App\get_upload_path($list['image']);
    }
    return $list;
  }

  public function addCollect($insert)
  {
    $list = \PhalApi\DI()->notorm->health_collect
      ->insert($insert);
    return $list;
  }


  public function deleteCollect($where = [])
  {
    $list = \PhalApi\DI()->notorm->health_collect
      ->where($where)->delete();
    return $list;
  }


  public function oneCollect($where = [])
  {
    $list = \PhalApi\DI()->notorm->health_collect
      ->where($where)->fetchOne();
    return $list;
  }
  public function userCollect($where, $selcet = '', $page, $num)
  {
    $list = \PhalApi\DI()->notorm->health_collect
      ->select('B.id,B.image,B.name')
      ->alias('A')->leftJoin('health', 'B', 'A.health_id = B.id')
      ->where($where)
      ->page($page, $num)->fetchAll();
    foreach ($list as $k => $v) {
      $v['image'] = \App\get_upload_path($v['image']);
      $list[$k] = $v;
    }
    return $list;
  }

  public function GetAll($where, $selcet = '')
  {
    $list = \PhalApi\DI()->notorm->health_collect
      ->select('B.id,B.image,B.name')
      ->alias('A')->leftJoin('health', 'B', 'A.health_id = B.id')
      ->where($where)
      ->fetchAll();
    foreach ($list as $k => $v) {
      $v['image'] = \App\get_upload_path($v['image']);
      $list[$k] = $v;
    }
    return $list;
  }
}
