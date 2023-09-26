<?php

/**
 * 订单支付回调
 */

namespace app\appapi\controller;

use app\models\OrdersModel;
use app\models\WxpayModel;
use cmf\controller\HomeBaseController;

class OrderpayController extends HomebaseController
{


  /* 微信支付 */

  public function notify_wx()
  {

    //$xmlInfo = $GLOBALS['HTTP_RAW_POST_DATA'];
    $xmlInfo = file_get_contents("php://input");

    WxpayModel::log("订单-微信APP支付");

    $arrayInfo = WxpayModel::notify_app($xmlInfo);

    $this->orderServer($arrayInfo, 2); //订单处理业务逻辑
    echo WxpayModel::returnInfo("SUCCESS", "OK");
    exit;
  }


  /* 订单查询加值业务处理
	 * @param orderNum 订单号	   
	 */
  private function orderServer($info, $paytype = '')
  {

    $where['paytype'] = $paytype;
    $where['orderno_pay'] = $info['out_trade_no'];

    $trade_no = $info['transaction_id'];

    $data = [
      'trade_no' => $trade_no
    ];

    WxpayModel::log("where:" . json_encode($where));
    $res = OrdersModel::handelPay($where, $data);
    if ($res == 0) {
      WxpayModel::log("orderno:" . $info['out_trade_no'] . ' 订单信息不存在');
      return false;
    }

    WxpayModel::log("成功");

    return true;
  }
}
