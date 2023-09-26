<?php
namespace App\Domain;

use App\Model\Usertoken as Model_Usertoken;
class Usertoken {

    public function getInfo($where,$field='*'){

        $model = new Model_Usertoken();
        $info = $model->getInfo($where,$field);
        return $info;
    }

    public function add($data) {

        $model = new Model_Usertoken();
        $result = $model->add($data);
        return $result;
    }

    public function up($where,$data) {

        $model = new Model_Usertoken();
        $result = $model->up($where,$data);

        return $result;
    }

    public function del($where){

        $model = new Model_Usertoken();
        $list = $model->del($where);

        return $list;
    }



}
