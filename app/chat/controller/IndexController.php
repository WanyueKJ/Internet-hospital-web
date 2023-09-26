<?php

namespace app\chat\Controller;

use app\models\OrdersModel;
use cmf\controller\AdminBaseController;


class IndexController extends AdminBaseController
{
  function index()
  {
    $param = $this->request->param();
    $webscoket = getConfigPri()['webscoket'];
    $this->assign('webscoket', $webscoket);

    $id = $param['id'] ?? '';
    if ($id == '') {
      $this->error('非法请求', url('admin/index/index'));
    }
    $info = (new OrdersModel)->alias('A')
      ->field('B.id as usid,B.user_nickname,B.avatar,C.name as doctor_name,A.*')
      ->join('users B', 'A.uid = B.id')
      ->join('doctor C', 'A.doctor_id = C.id')
      ->where('A.status', 2)
      ->where('A.type', 2)->select();
    $this->assign('info', $info);
    $this->assign('id', $id);
    return $this->fetch('chat@/index');
  }
}
