<?php

namespace app\admin\controller;

use app\admin\model\DoctorModel;
use app\admin\model\SignboardModel;
use cmf\controller\AdminBaseController;


class SignboardController extends AdminBaseController
{

    public function index(){
        $list = SignboardModel::paginate(20);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        return $this->fetch();
    }


    public function add(){
        return $this->fetch();
    }

    public function edit(){
        $id = $this->request->param('id',0);
        if(!$id){
            $this->error('非法请求');
        }
        $info = SignboardModel::get($id);
        $this->assign('info',$info);
        return $this->fetch();
    }
    public function addPost(){
        $param = $this->request->param();
        $validate = $this->validate($param,[
            'name|标识名称'=> 'require',
        ]);
        if($validate !== true){
            $this->error($validate);
        }
        $rs = SignboardModel::create($param,true);
        if(!$rs->id){
            $this->error('非法请求');
        }
        $this->success('添加成功');
    }
    public function editPost(){
        $param = $this->request->param();
        $validate = $this->validate($param,[
            'name|标识名称'=> 'require',
        ]);
        if($validate !== true){
            $this->error($validate);
        }
        $id = $param['id'] ?? '';
        if($id == ''){
            $this->error('非法请求');
        }
        $rs = SignboardModel::where('id',$id)->update(['name'=>$param['name']]);
        $this->success('更新成功');
    }
    public function delete(){
        $id = $this->request->param('id',0);
        if(!$id){
            $this->error('非法请求');
        }

        $result = DoctorModel::where('signboard_id',$id)->count();
        if($result){
            $this->error('无法删除,存在使用该标识的医生');
        }
        SignboardModel::destroy($id);
        $this->success('删除成功');
    }
}
