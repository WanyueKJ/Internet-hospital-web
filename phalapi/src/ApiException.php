<?php

namespace App;

use PhalApi\Exception;

/**
 * 接口异常提示类
 */
class ApiException extends Exception
{
    public function __construct($message, $code = 400, $data = [])
    {
        if (!$data) {
            $data = array('code' => $code, 'msg' => \PhalApi\T($message), 'info' => array());
        }
        \PhalApi\DI()->response->setData($data);

        parent::__construct(
            \PhalApi\T('{message}', array('message' => $message)), 200
        );

    }
}