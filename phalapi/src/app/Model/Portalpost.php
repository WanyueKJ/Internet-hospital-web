<?php

namespace App\Model;

use PhalApi\Model\NotORMModel;

class Portalpost extends NotORMModel
{
    public function list($where,$select = '*'){
        $list = \PhalApi\DI()->notorm->portal_post
                ->select($select)
                ->where($where)
                ->fetchAll();
        return $list;
    }
}