<?php

namespace App\Api;

use PhalApi\Api;

/**
 * 默认接口服务类
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */
class Site extends Api
{
    public function getRules()
    {
        return array(
            'index' => array(
                'username' => array('name' => 'username', 'default' => 'PhalApi', 'desc' => '用户名'),
            ),
        );
    }

    /**
     * 默认接口服务
     * @desc 默认接口服务，当未指定接口服务时执行此接口服务
     * @return string title 标题
     * @return string content 内容
     * @return string version 版本，格式：X.X.X
     * @return int time 当前时间戳
     * @exception 400 非法请求，参数传递错误
     */
    public function index()
    {
        return array(
            'title' => 'Hello ' . $this->username,
            'version' => PHALAPI_VERSION,
            'time' => $_SERVER['REQUEST_TIME'],
        );
    }




    public function past()
    {
        $nowtime = time();
        /* 定时处理订单过期未支付 */
        $key = 'orders_addtime';
        \App\zAdd($key, $nowtime, $nowtime);
        /* 定时处理订单过期未支付 */
    }


    public function order()
    {
        /* 新订单通知 */
        $key = 'orders_mer_new';
        \App\hSet($key, 26, 1);
        /* 新订单通知 */
    }
}
