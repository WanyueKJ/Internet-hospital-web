<?php

namespace app\models;

use think\Db;
use think\Model;

class OrdersModel extends Model
{
    protected $pk = 'id';
    protected $name = 'orders';

    public static function getAll($where,$field){

        $list=self::field($field)->where($where)->order('id desc')->select()->toArray();

        return $list;
    }

    public static function getStatus($k=''){
        $status=[
            '1'=>'未支付',
            '2'=>'已支付',
        ];

        if($k===''){
            return $status;
        }
        return  $status[$k] ?? '' ;
    }




    /* 处理支付订单 */
    public static function handelPay($where,$data=[]){

        $orderinfo=self::where($where)->find();
        if(!$orderinfo){
            return 0;
        }

        if($orderinfo['status']!=1){
            return 1;
        }

        $nowtime=time();
        /* 更新 订单状态 */
        $status=2;

        $data['status']=$status;
        $data['paytime']=$nowtime;
        $data['uptime']=$nowtime;

        self::where("id='{$orderinfo['id']}'")->update($data);

        return 2;
    }
}

