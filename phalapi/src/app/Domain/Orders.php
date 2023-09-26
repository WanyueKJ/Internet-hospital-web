<?php

namespace App\Domain;

use App\Model\Orders as Model_Orders;
use App\Model\Doctor;

class Orders
{

  public static function create($uid, $payid, $userInfo, $doctor_id, $servicetime, $remark, $type, $openid = "")
  {

    $rs = ["code" => 0, "msg" => \PhalApi\T("操作成功"), "info" => []];
    // $userInfo = '{
    //     "name":"小明",
    //     "card":370830200308085716,
    //     "phone":17562721943,
    //     "sex":"男",
    //     "weight":60,
    //     "age":18,
    //     "birth":"2023-2-2"
    // }';
    $info = json_decode($userInfo, true);
    if (!is_array($info)) {
      $rs["code"] = 2001;
      $rs["msg"] = "请正确填写用户信息";
      return $rs;
    }
    foreach ($info as $k => $v) {
      if ($k == 'phone') {
        if (!\App\checkMobile($v)) {
          $rs["code"] = 2001;
          $rs["msg"] = "请正确填写手机号";
          return $rs;
        }
      }
      if (empty($v)) {
        $rs["code"] = 2001;
        $rs["msg"] = "请正确填写用户信息";
        return $rs;
      }
    }
    $nowtime = time();
    $list = (new Doctor)->list(["id" => $doctor_id])[0] ?? "";
    if (!$list) {
      $rs["code"] = 2001;
      $rs["msg"] = "医生不存在";
      return $rs;
    }
    if (!in_array($type, [1, 2])) {
      $rs["code"] = 2001;
      $rs["msg"] = "非法请求";
      return $rs;
    }
    if ($type == 2) {
      $money = $list["online_cost"];
      if ($list["status"] == 0) {
        $rs["code"] = 2001;
        $rs["msg"] = "类型有误";
        return $rs;
      }
    }

    if ($type == 1) {
      $money = $list["cost"];
      $num = 0;
      $time_info = json_decode($list["info"], true);
      foreach ($time_info as $k2 => $v2) {
        $servicetime_ = $v2["time_start"] . "~" . $v2["time_end"];
        if ($servicetime_ === $servicetime) {
          $num = 1;
        }
      }
      if (!$num) {
        $rs["code"] = 2001;
        $rs["msg"] = "预约时间不存在";
        return $rs;
      }
    }


    $orderno = date("YmdHis") . rand(100, 999) . $uid;

    $order_data = [
      "type" => $type,
      "uid" => $uid,
      "orderno" => $orderno,
      "orderno_pay" => $orderno,
      "money" => $money,
      "servicetime" => $servicetime,
      "paytype" => $payid,
      "userInfo" => $userInfo,
      "remark" => $remark,
      "doctor_id" => $doctor_id,
      "addtime" => $nowtime,
      "uptime" => $nowtime,
      "status" => 1,
    ];


    \PhalApi\DI()->notorm->beginTransaction("db_master");
    if ($money == 0 && $payid != 0) {
      \PhalApi\DI()->notorm->rollback("db_master");
      $rs["code"] = 970;
      $rs["msg"] = \PhalApi\T("价格变动，请重新下单");
      return $rs;
    }



    $model = new Model_Orders();
    $res = $model->add($order_data);
    if ($res === false) {
      \PhalApi\DI()->notorm->rollback("db_master");
      $rs["code"] = 1006;
      $rs["msg"] = \PhalApi\T("下单失败，请重试");
      return $rs;
    }

    $oid = $res["id"];

    \PhalApi\DI()->notorm->commit("db_master");
    $res = self::pay($uid, $oid, $orderno, $money, $payid, $openid);

    return $res;
  }

  public static function pay($uid, $oid, $orderno, $money, $payid, $openid = "")
  {

    $rs = ["code" => 0, "msg" => \PhalApi\T("支付成功"), "info" => []];


    $info = Pay::pay($orderno, $money, $payid, "下单", "orderpay", $openid);

    $info["oid"] = $oid;
    $rs["info"][0] = $info;
    return $rs;
  }
}
