<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Usertoken extends NotORM {


	public function getInfo($where,$field='*') {
		$info=\PhalApi\DI()->notorm->users_token
				->select($field)
				->where($where)
				->fetchOne();
		return $info;
	}


	public function add($data){

        $rs=\PhalApi\DI()->notorm->users_token
                ->insert($data);

        return $rs;
	}

    public function up($where,$data){

        $rs=\PhalApi\DI()->notorm->users_token
            ->where($where)
            ->update($data);

        return $rs;
    }

    public function del($where){

        $rs=\PhalApi\DI()->notorm->users_token
            ->where($where)
            ->delete();

        return $rs;
    }




}
