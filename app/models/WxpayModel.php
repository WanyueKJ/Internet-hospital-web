<?php

namespace app\models;

class WxpayModel
{

  public static function notify_app($xmlInfo)
  {
    connectionRedis();
    $configpri = getConfigPri();

    $appid  =  $configpri['wx_appid'];
    $mch_id =  $configpri['wx_mchid'];
    $key =  $configpri['wx_key'];

    return self::check($appid, $mch_id, $key, $xmlInfo);
  }

  public static function notify_small($xmlInfo)
  {
    $configpri = getConfigPri();

    $appid  =  $configpri['small_appid'];
    $mch_id =  $configpri['small_mchid'];
    $key =  $configpri['small_key'];

    return self::check($appid, $mch_id, $key, $xmlInfo);
  }

  public static function notify_hfive($xmlInfo)
  {
    $configpri = getConfigPri();

    $appid  =  $configpri['pc_wx_appid'];
    $mch_id =  $configpri['pc_wx_mchid'];
    $key =  $configpri['pc_wx_key'];

    return self::check($appid, $mch_id, $key, $xmlInfo);
  }

  public static function notify_mp($xmlInfo)
  {
    $configpri = getConfigPri();

    $appid  =  $configpri['mp_appid'];
    $mch_id =  $configpri['mp_mchid'];
    $key =  $configpri['mp_key'];

    return self::check($appid, $mch_id, $key, $xmlInfo);
  }

  public static function check($appid, $mch_id, $key, $xmlInfo)
  {
    //解析xml
    $arrayInfo = self::xmlToArray($xmlInfo);

    self::log("wx_data:" . json_encode($arrayInfo)); //log打印保存

    if (!isset($arrayInfo['return_code']) || $arrayInfo['return_code'] != "SUCCESS") {
      echo self::returnInfo("FAIL", "签名失败");
      if (isset($arrayInfo['return_code'])) {
        self::log($arrayInfo['return_code']); //log打印保存
      }

      exit;
    }

    if (isset($arrayInfo['return_msg']) && $arrayInfo['return_msg'] != null) {
      echo self::returnInfo("FAIL", "签名失败");
      self::log("签名失败:"); //log打印保存
      exit;
    }

    $wxSign = $arrayInfo['sign'];
    unset($arrayInfo['sign']);
    $arrayInfo['appid']  =  $appid;
    $arrayInfo['mch_id'] =  $mch_id;

    ksort($arrayInfo); //按照字典排序参数数组
    $sign = self::sign($arrayInfo, $key); //生成签名

    if (!self::checkSign($wxSign, $sign)) {
      echo self::returnInfo("FAIL", "签名失败");
      self::log("签名验证结果失败:本地加密：" . $sign . '：：：：：三方加密' . $wxSign); //log打印保存
      exit;
    }

    return $arrayInfo;
  }

  public static function returnInfo($type, $msg)
  {
    if ($type == "SUCCESS") {
      return "<xml><return_code><![CDATA[{$type}]]></return_code></xml>";
    } else {
      return "<xml><return_code><![CDATA[{$type}]]></return_code><return_msg><![CDATA[{$msg}]]></return_msg></xml>";
    }
  }

  //签名验证
  public static function checkSign($sign1, $sign2)
  {
    return trim($sign1) == trim($sign2);
  }

  /**
   * sign拼装获取
   */
  public static function sign($param, $key)
  {

    $sign = "";
    foreach ($param as $k => $v) {
      $sign .= $k . "=" . $v . "&";
    }

    $sign .= "key=" . $key;
    $sign = strtoupper(md5($sign));
    return $sign;
  }
  /**
   * xml转为数组
   */
  public static function xmlToArray($xmlStr)
  {
    $msg = array();
    $postStr = $xmlStr;
    $msg = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
    return $msg;
  }

  /**
   * 数组转为xml
   */
  public static function arrayToXml($array)
  {
    $paramXml = "<xml>";
    foreach ($array as $k => $v) {
      $paramXml .= "<" . $k . ">" . $v . "</" . $k . ">";
    }
    $paramXml .= "</xml>";
    return $paramXml;
  }

  /* 打印log */
  public static function log($msg)
  {

    $path = CMF_ROOT . 'data/log/';

    if (!is_dir($path)) {
      mkdir($path, 0755, true);
    }

    file_put_contents($path . 'logwx_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . '  msg:' . $msg . "\r\n", FILE_APPEND);
  }

  public static function refund($paytype, $outTradeNo, $price, $outRefundNo, $notify_url, $reason = '')
  {
    $configpri = getConfigPri();

    $appid = $configpri['wx_appid'] ?? '';
    $mchid = $configpri['wx_mchid'] ?? '';
    $key = $configpri['wx_key'] ?? '';

    if ($paytype == 3) {
      $appid = $configpri['small_appid'] ?? '';
      $mchid = $configpri['small_mchid'] ?? '';
      $key = $configpri['small_key'] ?? '';
    }

    if ($paytype == 4) {
      $appid = $configpri['pc_wx_appid'] ?? '';
      $mchid = $configpri['pc_wx_mchid'] ?? '';
      $key = $configpri['pc_wx_key'] ?? '';
    }

    if ($paytype == 5) {
      $appid = $configpri['mp_appid'] ?? '';
      $mchid = $configpri['mp_mchid'] ?? '';
      $key = $configpri['mp_key'] ?? '';
    }

    //配置参数检测
    if ($appid == "" || $mchid == "" || $key == "") {
      return '微信未配置';
    }

    $noceStr = md5(rand(100, 1000) . time()); //获取随机字符串
    $param = array(
      "appid" => $appid,
      "mch_id" => $mchid,
      "nonce_str" => $noceStr,
      "out_trade_no" => $outTradeNo,
      //"refund_desc" => $reason,
      "notify_url" => $notify_url,
      "total_fee" => $price * 100,
      "out_refund_no" => $outRefundNo,
      "refund_fee" => $price * 100,
    );

    if ($reason != '') {
      $param['refund_desc'] = $reason;
    }

    $sign = self::sign($param, $key); //生成签名
    $param['sign'] = $sign;

    $paramXlm = self::arrayToXml($param);

    //证书
    $keyPath = CMF_ROOT . "wanyue/wxpay/apiclient_key.pem";
    $certPath = CMF_ROOT . "wanyue/wxpay/apiclient_cert.pem";

    //        $keyPath = get_upload_path($configpri['wx_key_file']);
    //        $certPath = get_upload_path($configpri['wx_key_file']);

    if (!file_exists($keyPath) || !file_exists($certPath)) {
      return "微信证书未配置,无法退款";
    }
    $requestUrl = "https://api.mch.weixin.qq.com/secapi/pay/refund";
    $result = curl_post($requestUrl, $paramXlm, [], $certPath, $keyPath);
    $resultArr = self::xmlToArray($result);

    if (!is_array($resultArr)) {
      return "申请退款失败";
    }
    if (!array_key_exists('result_code', $resultArr)) {
      return "申请退款失败";
    }
    if ($resultArr['result_code'] != 'SUCCESS') {
      return $resultArr['err_code_des'] ?? "申请退款失败";
    }

    return 1;
  }

  /**
   * 微信退款回调解密
   * @param $notifyArray
   * @return void
   */
  public static function refund_decryption($xmlInfo)
  {

    $notifyArray = self::xmlToArray($xmlInfo);

    $req_info = $notifyArray['req_info'];
    $strB = base64_decode($req_info, true);
    $wx_key = self::getMchKey($notifyArray['appid']);
    $md5Key = strtolower(md5($wx_key));
    $decodeXml = openssl_decrypt($strB, 'aes-256-ecb', $md5Key, OPENSSL_RAW_DATA);
    $decodeArray = self::xmlToArray($decodeXml);
    self::log('data:' . json_encode($decodeArray));
    if (!is_array($decodeArray)) {
      return [];
    }

    return $decodeArray;
  }

  /**
   * 获取对应的商户KEY
   * @param $appId
   * @param $mchId
   * @return void
   */
  public static function getMchKey($appId, $mchId = "")
  {
    $configpri = getConfigPri();

    $appidName = '';
    foreach ($configpri as $key => $value) {
      if ($appId == $value) {
        $appidName = $key;
        break;
      }
    }
    if (!$appidName) return "";
    $endNum = strrpos($appidName, '_');
    $cutOut = substr($appidName, 0, $endNum);
    $mchKey = $cutOut . '_key';
    return $configpri[$mchKey] ?? '';
  }
}
