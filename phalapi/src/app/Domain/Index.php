<?php

namespace App\Domain;


class Index
{
  public function index()
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());
    $harousel = (new \App\Domain\Slide)->getSlide(2);
    $health = (new \App\Model\Health)->list(['type' => 1], 1, 3);
    $video = (new \App\Model\Health)->list(['type' => 2], 1, 3);
    $where = ['post_type' => 2, 'type' => 2, 'id ' => [9, 10, 11, 13]];
    $list = (new \App\Model\Portalpost)->list($where, 'id,post_title');
    foreach ($list as $k => $v) {
      $list[$k]['host'] = \App\get_host() . "/appapi/page/detail?id=" . $v['id'];
    }
    $info['harousel'] = $harousel;
    $info['health'] = $health;
    $info['video'] = $video;
    $info['href'] = $list;
    $rs['info'] = $info;
    return $rs;
  }
}
