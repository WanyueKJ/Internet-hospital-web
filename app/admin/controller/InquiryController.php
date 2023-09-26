<?php

namespace app\admin\controller;

use app\models\OrdersModel;
use cmf\controller\AdminBaseController;

class InquiryController extends AdminBaseController
{
  public function index()
  {
    $param = $this->request->param();
    $keyword = $param['keyword'] ?? '';
    $status = $param['status'] ?? '';
    $map = [];
    $where = [];
    if ($keyword != '') {
      $map[] = ['C.name', 'like', "%{$keyword}%"];
    }
    if ($status != '') {
      $where['A.status'] = [$status];
    }
    $list = (new OrdersModel)->alias('A')
      ->field('B.user_nickname,C.name as doctor_name,A.*')
      ->join('users B', 'A.uid = B.id')
      ->join('doctor C', 'A.doctor_id = C.id')
      ->where('A.type', 2)
      ->where($where)
      ->where($map)
      ->order('A.id desc')
      ->paginate(20);
    $list->each(function ($v, $k) {
      $v['status_s'] = $v['status'];
      $v['status'] = OrdersModel::getStatus($v['status']);
      if ($v['paytime']) {
        $v['paytime'] = date('Y-m-d H:i:s', $v['paytime']);
      } else {
        $v['paytime'] = '---';
      }
    });
    $this->assign('type', 2);
    $this->assign('list', $list);
    $this->assign('page', $list->render());
    return $this->fetch('registration/index');
  }
}
