<?php

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Orders extends NotORM
{

  /* 添加 */
  public function add($data)
  {
    return \PhalApi\DI()->notorm->orders
      ->insert($data);
  }

  public function feacall($where, $select = '*', $page, $num)
  {
    $list = \PhalApi\DI()->notorm->orders
      ->alias('A')->leftJoin('doctor', 'B', 'A.doctor_id = B.id')
      ->select($select)->where($where)
      ->page($page, $num)
      ->fetchAll();
    return $list;
  }

  public function userinfo($where, $select = '*')
  {
    $list = \PhalApi\DI()->notorm->orders->where($where)->select($select)->fetchOne();
    return $list;
  }
}
