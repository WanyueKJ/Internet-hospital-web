<?php
namespace App\Api;
use PhalApi\Api;
use App\Domain\Orders as Domain_Orders;
/**
 * 订单
 */
class Order extends Api{
    public function getRules(){
        return [
            'create' => array(
                'userInfo' => array('name' => 'userInfo', 'type' => 'string', 'desc' => '用户信息',),
                'doctor_id'=>array('name' => 'doctor_id', 'type' => 'int', 'desc' => '医生id'),
                'servicetime'=>array('name' => 'servicetime', 'type' => 'string', 'desc' => '就诊时间'),
                'remark'=>array('name' => 'remark', 'type' => 'string', 'desc' => '备注'),
                'type'=>array('name' => 'type', 'type' => 'int', 'desc' => '1预约 2问诊','require'=>true),
                'openid' => array('name' => 'openid', 'type' => 'string', 'desc' => 'openid'),
            ),
        ];
    }

    /**
     * 下单
     * @desc 用于下单
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[].oid  订单ID
     * @return string info[].orderno  订单号
     * @return string info[].money  金额
     * @return string msg 提示信息
     */
    public function create(){

        $uid = \App\checkNull($this->uid);
        $token = \App\checkNull($this->token);
        $checkToken = \App\checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        
        $payid = 2;
        $userInfo =\App\checkNull($this->userInfo);
        $doctor_id =\App\checkNull($this->doctor_id);
        $servicetime =\App\checkNull($this->servicetime);
        $remark =\App\checkNull($this->remark);
        $type =\App\checkNull($this->type);
        $openid=\App\checkNull($this->openid);
        $domain = new Domain_Orders();
		return $domain->create($uid,$payid,$userInfo,$doctor_id,$servicetime,$remark,$type,$openid);
    }


    
}