<?php

namespace app\admin\controller;

use app\models\OrdersModel;
use cmf\controller\AdminBaseController;

class RegistrationController extends AdminBaseController
{
  public function index()
  {
    $param = $this->request->param();
    $keyword = $param['keyword'] ?? '';
    $status = $param['status'] ?? '';

    $where = [];
    $map = [];
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
      ->where('A.type', 1)
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
    $this->assign('type', 1);
    $this->assign('list', $list);
    $this->assign('page', $list->render());
    return $this->fetch();
  }

  public function complete()
  {
    $param = $this->request->param();
    $id = $param['id'] ?? '';
    if ($id == '') {
      $this->error('信息有误');
    }
    $rs = (new OrdersModel)->get($id);
    if (!$rs) {
      $this->error('非法请求');
    }
    if ($rs['status'] == 1) {
      $this->error('订单未支付');
    }
    $rs->complete = 1;
    if (!$rs->save()) {
      $this->error('信息有误');
    }
    $this->success('核销成功');
  }


  public function read()
  {
    $param = $this->request->param();
    $id = $param['id'] ?? '';
    $type = $param['type'] ?? '';
    if ($id == '' || $type == '') {
      $this->error('信息有误');
    }
    $rs = (new OrdersModel)->get($id);
    if (!$rs) {
      $this->error('非法请求');
    }
    $userInfo = json_decode($rs['userInfo'], true);
    unset($userInfo['id']);
    unset($userInfo['uid']);
    $info_ = ['name' => '姓名', 'card' => '身份证', 'phone' => '手机号', 'sex' => '性别', 'weight' => '体重', 'age' => '年龄', 'birth' => '出生年月'];
    $div = '';
    foreach ($userInfo as $k => $v) {

      $title = $info_[$k];
      if ($k == 'weight') {
        $v = $v . 'kg';
      }
      // if ($k == 'age') {
      //   $v = $v . '岁';
      // }
      $div = $div . "<div class=\"form-group\">
                        <label for=\"input-name\" class=\"col-sm-2 control-label\"><span class=\"form-required\">*</span>{$title}</label>
                        <div class=\"col-md-6 col-sm-10\">
                            <input type=\"text\" class=\"form-control\" id=\"input-name\" disabled value=\"{$v}\">
                        </div>
                    </div>";
    }
    if ($type == 2) {
      $remark = $rs['remark'];
      $div .= "<div class=\"form-group\">
                    <label for=\"input-name\" class=\"col-sm-2 control-label\"><span class=\"form-required\">*</span>备注</label>
                    <div class=\"col-md-6 col-sm-10\">
                        <textarea class=\"form-control\" disabled>{$remark}</textarea>
                    </div>
                </div";
    }
    $this->assign('div', $div);
    $this->assign('type', $type);
    $this->assign('info', $rs);
    return $this->fetch();
  }
}
