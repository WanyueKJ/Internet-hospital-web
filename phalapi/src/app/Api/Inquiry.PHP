<?php
namespace App\Api;
use PhalApi\Api;


/**
 * 问诊
 */
class Inquiry extends Api{
    public function getRules(){
        return [
            'InquiryList'=>[],
        ];
    }

    /**
     * 问诊医生列表
     *
     * @return void
     */
    public function InquiryList(){
        $rs = array('code' => 0, 'msg' =>'', 'info' => array());
        $uid = \App\checkNull($this->uid);
        $token = \App\checkNull($this->token);
        $checkToken = \App\checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

    }
}