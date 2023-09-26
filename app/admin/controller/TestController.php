<?php

namespace app\admin\controller;

use cmf\controller\AdminBaseController;

class TestController extends AdminBaseController
{
  public function index()
  {
    function Dblink($config)
    {
      $host = $config['host'];
      $post = $config['port'];
      $user = $config['user'];
      $password = $config['password'];
      $database = $config['database'];
      $link = mysqli_connect($host, $user, $password, $database, $post);
      mysqli_set_charset($link, "utf8");
      return $link;
    }
    $link = Dblink([
      'host'     => '127.0.0.1',
      'port'     => 3306,
      'user'     => 'kyhospital_sdwan',
      'password' => 'cwLXAM56HNCH4C88',
      'database' => 'kyhospital_sdwan',
    ]);
    //查询单条的方法
    function getOne($link, $where, $fields = '*', $table = 'cmf_chat')
    {
      $sql = "select ";
      $sql .= $fields;
      $sql .= " from " . $table;
      $sql .= " where " . $where;
      $result = mysqli_query($link, $sql);

      if ($result) {
        return mysqli_fetch_assoc($result);
      } else {
        return false;
      }
    }
    $name = json_decode(getOne($link, "id = 38", 'userInfo', 'cmf_orders')['userInfo'], true)['name'] ?? '';
    return $name;
  }
}
