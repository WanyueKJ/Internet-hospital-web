<?php

namespace App\Domain;

use App\Model\Slide as Model_Slide;

class Slide
{

  /* 轮播 */
  public function getSlide($id = '1')
  {

    $key = 'getSlide_' . $id;
    if (isset($GLOBALS[$key])) {
      return $GLOBALS[$key];
    }
    $list = \App\getcaches($key);
    if (!$list) {
      $model = new Model_Slide();
      $list = $model->getAll($id);
      \App\setcaches($key, $list);
    }
    foreach ($list as $k => $v) {
      $v['image'] = \App\get_upload_path($v['image']);
      $list[$k] = $v;
    }
    $GLOBALS[$key] = $list;
    return $list;
  }
}
