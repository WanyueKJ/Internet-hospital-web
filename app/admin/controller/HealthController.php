<?php

namespace app\admin\controller;

use app\admin\model\HealthModel;
use cmf\controller\AdminBaseController;

class HealthController extends AdminBaseController
{
  public function index()
  {
    $param = $this->request->param();
    $id = $param['id'] ?? '';
    $keyword = $param['keyword'] ?? '';
    $where = [];
    if ($keyword  !== '') {
      $where[] = ['name', 'like', "%$keyword%"];
    }
    $list = HealthModel::order('sort')->where($where)->where('type', 1)->paginate(20);
    $this->assign('list', $list);
    $this->assign('page', $list->render());
    return $this->fetch();
  }


  public function add()
  {
    return $this->fetch();
  }

  public function edit()
  {
    $id = $this->request->param('id', 0);
    if (!$id) {
      $this->error('非法请求');
    }
    $info = HealthModel::get($id);
    $info['content'] = html_entity_decode($info['content']);
    $this->assign('info', $info);
    return $this->fetch();
  }

  public function editPost()
  {
    $param = $this->request->param();
    $validate = $this->validate($param, [
      'name|标题' => 'require',
      'content|内容' => 'require',
      'image|封面' => 'require'
    ]);
    if ($validate !== true) {
      $this->error($validate);
    }
    $id = $param['id'] ?? '';
    if ($id == '') {
      $this->error('非法请求');
    }
    $param['uptime'] = time();
    (new HealthModel)->save($param, ['id' => $param['id']]);
    $this->success('更新成功');
  }


  public function addPost()
  {
    $param = $this->request->param();
    $validate = $this->validate($param, [
      'name|标题' => 'require',
      'content|内容' => 'require',
      'image|封面' => 'require'
    ]);
    if ($validate !== true) {
      $this->error($validate);
    }
    $param['addtime'] = time();
    $param['sort'] = 1000;
    $param['type'] = 1;
    $rs = HealthModel::create($param, true);
    if (!$rs->id) {
      $this->error('非法请求');
    }
    $this->success('发布成功');
  }


  public function listOrder()
  {
    $param = $this->request->param();
    $validate = $this->validate($param, [
      'sort' => 'require'
    ]);
    if ($validate !== true) {
      $this->error($validate);
    }
    $list = [];
    $sort_ = $param['sort'];
    foreach ($sort_ as $k => $v) {
      $list[] = ['id' => $k, 'sort' => $v];
    }
    $rs = (new HealthModel)->saveAll($list, true);
    if (!$rs) {
      $this->error('非法请求');
    }
    $this->success('发布成功');
  }

  public function delete()
  {
    $id = $this->request->param('id', 0);
    if (!$id) {
      $this->error('非法请求');
    }
    HealthModel::destroy($id);
    $this->success('删除成功');
  }
}
