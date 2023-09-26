<?php
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

//插入聊天记录
function insert($link, $field, $vlaues, $table = 'cmf_chat')
{
  $sql = "insert into {$table} ({$field}) values ({$vlaues})";
  mysqli_query($link, $sql);
}


//修改订单状态
function update($link, $id)
{
  $sql = "update cmf_orders set complete = 1 WHERE type = 2 and id = {$id}";
  mysqli_query($link, $sql);
}


//查询多条方法
function getAll($link, $where, $fields = '*', $table = 'cmf_chat')
{
  $sql = "select ";
  $sql .= $fields;
  $sql .= " from " . $table;
  $sql .= " where " . $where;

  $result = mysqli_query($link, $sql);
  //以关联数组形式返回
  return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

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
