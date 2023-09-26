<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class User extends NotORM {

    public function getInfo($where,$field='*'){

        $info=\PhalApi\DI()->notorm->users
            ->select($field)
            ->where($where)
            ->fetchOne();

        return $info;
    }

    public function getCountryCode($where,$group = '',$field='*'){

        $info=\PhalApi\DI()->notorm->country_code
            ->select($field)
            ->where($where)
            ->group($group)
            ->fetchAll();

        return $info;
    }

	public function add($data){
        $rs=\PhalApi\DI()->notorm->users
            ->insert($data);

        return $rs;
	}


	public function up($where,$data){
        $rs=\PhalApi\DI()->notorm->users
            ->where($where)
            ->update($data);
        return $rs;
	}

	public function upField($where,$field,$nums){
        $rs=\PhalApi\DI()->notorm->users
            ->where($where)
            ->update(["{$field}" => new \NotORM_Literal("{$field} + {$nums}")]);
        return $rs;
	}

	public function del($where){
        $rs=\PhalApi\DI()->notorm->users
            ->where($where)
            ->delete();

        return $rs;
	}


    public function getAll($where,$field='*'){

        $list=\PhalApi\DI()->notorm->users
				->select($field)
                ->where($where)
				->fetchAll();

        return $list;
    }

}
