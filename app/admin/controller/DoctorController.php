<?php

namespace app\admin\controller;

use app\admin\model\DoctorModel;
use app\admin\model\SignboardModel;
use cmf\controller\AdminBaseController;

class DoctorController extends AdminBaseController
{


    public function index()
    {
        $param = $this->request->param();
        $keyword = $param['keyword'] ?? '';
        $where = [];
        if ($keyword  !== '') {
            $where[] = ['name', 'like', "%$keyword%"];
        }
        $list = DoctorModel::order('sort')->where($where)->paginate(20);
        $this->assign('list', $list);
        $this->assign('page', $list->render());
        return $this->fetch();
    }


    public function add()
    {

        $info = SignboardModel::select();
        $this->assign('info', $info);
        return $this->fetch();
    }

    public function addpost()
    {
        $param = $this->request->param();
        $validate = $this->validate($param, [
            'name|名称' => 'require',
            'professional|职称' => 'require',
            'signboard_id|标识' => 'require',
            'img|头像' => 'require',
            'content|介绍' => 'require',
            'message|擅长' => 'require',
        ]);
        if ($validate !== true) {
            $this->error($validate);
        }
        if (!is_numeric($param['cost']) || !is_numeric($param['online_cost'])) {
            $this->error('费用填写有误');
        }
        if ($param['cost'] < 0.01 || $param['online_cost'] < 0.01) {
            $this->error('最低金额为0.01元');
        }
        $info = $param['info'];
        foreach ($info as $k => $v) {
            $time_start = strtotime($v['time_start']);
            $time_end = strtotime($v['time_end']);
            if ($time_end < $time_start) {
                $this->error('预约结束时间不能小于开始时间');
            }
            if (empty($v['time_start'])) {
                $this->error('请正确填写时间段');
            }
            if (empty($v['time_end'])) {
                $this->error('请正确填写时间段');
            }
        }
        $param['info'] = json_encode($param['info']);
        $rs = DoctorModel::create($param, true);
        if (!$rs->id) {
            $this->error('非法请求');
        }
        $this->success('添加成功');
    }


    public function edit()
    {
        $id = $this->request->param('id', 0);
        if (!$id) {
            $this->error('非法请求');
        }

        $signboard = SignboardModel::select();
        $this->assign('signboard', $signboard);
        $info = DoctorModel::get($id);
        $info['info'] = json_decode($info['info'], true);
        $this->assign('info', $info);
        return $this->fetch();
    }


    public function editpost()
    {
        $param = $this->request->param();
        $validate = $this->validate($param, [
            'name|名称' => 'require',
            'professional|职称' => 'require',
            'signboard_id|标识' => 'require',
            'img|头像' => 'require',
            'content|介绍' => 'require',
            'message|擅长' => 'require',
        ]);
        if ($validate !== true) {
            $this->error($validate);
        }
        if (!is_numeric($param['cost']) || !is_numeric($param['online_cost'])) {
            $this->error('费用填写有误');
        }
        if ($param['cost'] < 0.01 || $param['online_cost'] < 0.01) {
            $this->error('最低金额为0.01元');
        }
        $info = $param['info'];
        foreach ($info as $k => $v) {
            $time_start = strtotime($v['time_start']);
            $time_end = strtotime($v['time_end']);
            if ($time_end < $time_start) {
                $this->error('预约结束时间不能小于开始时间');
            }
            if (empty($v['time_start'])) {
                $this->error('请正确填写时间段');
            }
            if (empty($v['time_end'])) {
                $this->error('请正确填写时间段');
            }
        }
        $id = $param['id'] ?? '';
        if ($id == '') {
            $this->error('非法请求');
        }
        $param['info'] = json_encode($param['info']);
        $rs = DoctorModel::where('id', $id)->update($param);
        $this->success('更新成功');
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
        $rs = listOrder($param, DoctorModel::class);
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
        DoctorModel::destroy($id);
        $this->success('删除成功');
    }


    public function status()
    {
        $param = $this->request->param();
        $status = $param['status'] ?? '';
        $id = $param['id'] ?? '';
        if ($status == '' || $id == '') {
            $this->error('信息有误');
        }
        if (!in_array($status, [0, 1])) {
            $this->error('非法提交');
        }
        DoctorModel::where('id', $id)->update(['status' => $status]);
        $this->success('成功');
    }

}
