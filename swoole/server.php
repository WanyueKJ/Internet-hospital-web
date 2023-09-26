<?php
require_once dirname(__FILE__) . '/redis.php';
require_once dirname(__FILE__) . '/sql.php';
class Websocket
{
  public $link;

  private $server;


  public function __construct()
  {
    $this->init();
  }



  public function onOpen($server, $request)
  {
    $fd = $request->fd;
    $fid = $request->get['fid'] ?? '';
    $tid = $request->get['tid'] ?? '';
    $oid = $request->get['oid'] ?? '';


    if ($fid === '' || $tid === '' || $oid === '') {
      $arr = array('status' => 0, 'message' => '信息有误', 'data' => '');
      $server->push($fd, json_encode($arr));
      $server->close($fd);
      return;
    }

    $this->saveFdCache($fid, $tid, $oid, $fd);

    $ain = getAll($this->link, "fid = {$fid} and orders = {$oid} and tid = {$tid}");
    $other = getAll($this->link, "fid = {$tid} and orders = {$oid} and tid = {$fid}");
    $userInfo = json_decode(getOne($this->link, "id = {$oid}", 'userInfo', 'cmf_orders')['userInfo'], true);
    $name = $userInfo['name'] ?? '';
    foreach ($ain as $k => $v) {
      $ain[$k]['signboard'] = 'ain';
      $ain[$k]['name'] = $name;
      $ain[$k]['sort'] = $ain[$k]['addtime'];
      $ain[$k]['addtime'] = date('Y-m-d H:i:s', $ain[$k]['addtime']);
    }

    foreach ($other as $k => $v) {
      $other[$k]['signboard'] = 'other';
      $other[$k]['sort'] = $other[$k]['addtime'];
      $other[$k]['addtime'] = date('Y-m-d H:i:s', $other[$k]['addtime']);
    }
    $vl = array_merge($ain, $other);
    $sort_ = array_column($vl, 'sort');
    array_multisort($sort_, SORT_ASC, $vl);
    $arr =  array('status' => 1, 'message' => 'success', 'data' => $vl);

    $complete = getOne($this->link, "id = {$oid}", 'complete', 'cmf_orders')['complete'] ?? 0;
    if ($complete) {
      $arr['status'] = 2;
    }
    $server->push($fd, json_encode($arr));
  }


  /**
   * wss://kyhospital.sdwanyue.com:9500/?oid=29&tid=0&fid=7&token=896a26f6d2991a6c0af5a25325138e37
   * data格式 = {"fid":7,"tid":0,"oid":29,"content":"xxxxxxxxxxxxxxx"}
   * @param $server
   * @param $frame
   */
  public function onMessage($server, $frame)
  {
    $fd = $frame->fd;
    $message = json_decode($frame->data, true);
    $fid = $message['fid'] ?? '';
    $tid = $message['tid'] ?? '';
    $oid = $message['oid'] ?? '';

    if ($fid !== '' && $tid !== '' && $oid !== '') {
      $tfd = $this->getFdcache($tid, $fid, $oid);

      $data['fid'] = $fid;
      $data['tid'] = $tid;
      $data['content'] = $message['content'];
      $data['addtime'] = time();
      $data['signboard'] = 'ain';

      if ($data['content'] == 'close' && $fd !== 0) {
        $arr = array('status' => 2, 'message' => '订单已完成', 'data' => '');
        $server->push($fd, json_encode($arr));
        $server->push($tfd, json_encode($arr));
        update($this->link, $oid);
        $server->close($fd);
        return;
      }

      $arr = array('status' => 1, 'message' => 'success', 'data' => $data);
      $this->addChatRecord($fid, $tid, $message['content'], $oid, $data['addtime']);

      // $server->push($tfd, json_encode($arr));

      $fds = [];
      foreach ($server->connections as $fd) {
        array_push($fds, $fd);
      }

      if (in_array($tfd, $fds)) {
        $data['signboard'] = 'other';
        $arr['data']['addtime'] = date('Y-m-d H:i:s', $arr['data']['addtime']);
        $server->push($tfd, json_encode($arr));
      } else {
      }
    } else {
      $arr = array('status' => 0, 'message' => '信息有误', 'data' => '');
      $server->push($fd, json_encode($arr));
      return;
    }
  }

  public function saveFdCache($fid, $tid, $oid, $fd)
  {
    $value = ['fid' => $fid, 'tid' => $tid, 'fd' => $fd];
    setcaches('fid' . $fid . 'tid' . $tid . 'oid' . $oid, $value, 1800);
  }



  public function getFdcache($fid, $tid, $oid)
  {
    $data = getcaches('fid' . $fid . 'tid' . $tid . 'oid' . $oid);
    return $data['fd'];
  }




  public function addChatRecord($fid, $tid, $content, $orders, $addtime)
  {

    $values = "{$fid} ,{$tid}, '{$content}' ,{$addtime}, {$orders}";
    insert($this->link, 'fid,tid,content,addtime,orders', $values);
  }


  public function init()
  {

    $config = require_once dirname(__FILE__) . '/config.php';

    $websocket = $config['websocket'];

    $redis = $config['redis'];

    $mysql = $config['mysql'];

    $this->link = Dblink($mysql);

    connectionRedis($redis);

    $this->server = new Swoole\WebSocket\Server($websocket['host'], $websocket['post'], SWOOLE_PROCESS, SWOOLE_SOCK_TCP | SWOOLE_SSL);

    $this->server->set($websocket['option']);

    $this->server->on('open', [$this, 'onOpen']);

    $this->server->on('message', [$this, 'onMessage']);

    $this->server->start();
  }
}

new Websocket;
