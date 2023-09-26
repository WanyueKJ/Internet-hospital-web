<?php
namespace App\Domain;

class Wxpay {

    /* APP微信支付
    *  orderid  订单号
    *  money    CNY（元）
    *  url      回调URL（全链接）
    *  body     提示标题
    */
    public static function wxPay($orderid,$money,$url,$body='充值虚拟币') {

        $configpri = \App\getConfigPri();
        $appid=$configpri['wx_appid'] ?? '';
        $mchid=$configpri['wx_mchid'] ?? '';
        $key=$configpri['wx_key'] ?? '';
        //配置参数检测
        if($appid== "" || $mchid== "" || $key== ""){
            \App\error(1002,'微信未配置');
        }

        $noceStr = md5(rand(100,1000).time());//获取随机字符串

        $paramarr = array(
            "appid"       =>    $appid,
            "body"        =>    $body,
            "mch_id"      =>    $mchid,
            "nonce_str"   =>    $noceStr,
            "notify_url"  =>    $url,
            "out_trade_no"=>    $orderid,
            "total_fee"   =>    $money*100,
            "trade_type"  =>    "APP"
        );
        $sign = self::sign($paramarr,$key);//生成签名
        $paramarr['sign'] = $sign;

        $result2 = self::unifiedorder($paramarr);

        $time2 = time();
        $prepayid = $result2['prepay_id'];
        $noceStr = md5(rand(100,1000).time());//获取随机字符串
        $paramarr2 = array(
            "appid"     =>  $appid,
            "noncestr"  =>  $noceStr,
            "package"   =>  "Sign=WXPay",
            "partnerid" =>  $mchid,
            "prepayid"  =>  $prepayid,
            "timestamp" =>  $time2
        );
        $paramarr2["sign"] = self::sign($paramarr2,$key);//生成签名

        return $paramarr2;
    }

    /* 小程序支付
    *  orderid  订单号
    *  money    CNY（元）
    *  url      回调URL（全链接）
    *  body     提示标题
    */
    public static function smallPay($orderid,$money,$url,$body='充值虚拟币', $openid='') {

        $configpri = \App\getConfigPri();
        $appid=$configpri['small_appid'] ?? '';
        $mchid=$configpri['small_mchid'] ?? '';
        $key=$configpri['small_key'] ?? '';

        //配置参数检测
        if($appid== "" || $mchid== "" || $key== ""){
            \App\error(1003,'小程序未配置');
        }

        $noceStr = md5(rand(100,1000).time());//获取随机字符串
        $time = time();

        $paramarr = array(
            "appid"       =>   $appid,
            "body"        =>    $body,
            "mch_id"      =>    $mchid,
            "nonce_str"   =>    $noceStr,
            "notify_url"  =>    $url,
            "out_trade_no"=>    $orderid,
            "total_fee"   =>    $money*100,
            "trade_type"  =>    "JSAPI",
            'openid'     =>     $openid,
        );

        $sign = self::sign($paramarr,$key);//生成签名
        $paramarr['sign'] = $sign;

        $result2 = self::unifiedorder($paramarr);

        $time2 = time();
        $prepayid = $result2['prepay_id'];
        $noceStr = md5(rand(100,1000).time());//获取随机字符串
        //二次验签
        $paramarr2 = array(
            "appId"     =>  $appid,
            "nonceStr"  =>  $noceStr, //微信官方推荐用官方接口返回的随机串, 用自己生成的也行
            "package"   =>  'prepay_id='.$prepayid,
            "signType"  =>  'MD5',
            "timeStamp" =>  ''.$time2
        );
        $paramarr2["sign"] = self::sign($paramarr2,$key);//生成签名

        return $paramarr2;
    }

    /* 微信外h5支付
    *  orderid  订单号
    *  money    CNY（元）
    *  url      回调URL（全链接）
    *  body     提示标题
    */
    public static function hfivePay($orderid,$money,$url,$body='充值虚拟币', $openid='') {

        $configpri = \App\getConfigPri();
        $appid=$configpri['wx_appid'] ?? '';
        $mchid=$configpri['wx_mchid'] ?? '';
        $key=$configpri['wx_key'] ?? '';

        //配置参数检测
        if($appid== "" || $mchid== "" || $key== ""){
            \App\error(1002,'h5未配置');
        }

        $noceStr = md5(rand(100,1000).time());//获取随机字符串

        $paramarr = array(
            "appid"       =>   $appid,
            "body"        =>    $body,
            "mch_id"      =>    $mchid,
            "nonce_str"   =>    $noceStr,
            "notify_url"  =>    $url,
            "out_trade_no"=>    $orderid,
            "total_fee"   =>    $money*100,
            "trade_type"  =>    "MWEB",
            'spbill_create_ip' => self::getClientIp(), //用户终端真实IP地址
        );

        $sign = self::sign($paramarr,$key);//生成签名
        $paramarr['sign'] = $sign;

        $result2 = self::unifiedorder($paramarr);

        $time2 = time();
        $prepayid = $result2['prepay_id'];
        $sign = "";
        $noceStr = md5(rand(100,1000).time());//获取随机字符串
        //二次验签
        $paramarr2 = array(
            "appId"     =>  $appid,
            "nonceStr"  =>  $noceStr, //微信官方推荐用官方接口返回的随机串, 用自己生成的也行
            "package"   =>  'prepay_id='.$prepayid,
            "signType"  =>  'MD5',
            "timeStamp" =>  ''.$time2,
            "mweb_url"  =>  $result2['mweb_url']
        );

        $paramarr2["sign"] = self::sign($paramarr2,$key);//生成签名

        return $paramarr2;
    }

    /* 微信PC web支付
    *  orderid  订单号
    *  money    CNY（元）
    *  url      回调URL（全链接）
    *  body     提示标题
    */
    public static function webPay($orderid,$money,$url,$body='充值虚拟币') {

        $configpri = \App\getConfigPri();
        $appid=$configpri['wx_appid'] ?? '';
        $mchid=$configpri['wx_mchid'] ?? '';
        $key=$configpri['wx_key'] ?? '';

        //配置参数检测
        if($appid== "" || $mchid== "" || $key== ""){
            \App\error(1002,'WEB未配置');
        }

        $noceStr = md5(rand(100,1000).time());//获取随机字符串

        $paramarr = array(
            "appid"       =>   $appid,
            "body"        =>    $body,
            "mch_id"      =>    $mchid,
            "nonce_str"   =>    $noceStr,
            "notify_url"  =>    $url,
            "out_trade_no"=>    $orderid,
            "total_fee"   =>    $money*100,
            "trade_type"  =>    "NATIVE",
        );

        $sign = self::sign($paramarr,$key);//生成签名
        $paramarr['sign'] = $sign;

        $result2 = self::unifiedorder($paramarr);

        //二次验签
        $paramarr2 = array(
            "code_url"  =>  $result2['code_url']
        );

        return $paramarr2;
    }

    /* 微信内支付
    *  orderid  订单号
    *  money    CNY（元）
    *  url      回调URL（全链接）
    *  body     提示标题
    */
    public static function mpPay($orderid,$money,$url,$body='充值虚拟币', $openid='') {

        $configpri = \App\getConfigPri();

        $appid= $configpri['mp_appid'] ?? '';
        $mchid= $configpri['mp_mchid'] ?? '';
        $key= $configpri['mp_key'] ?? '';
        //配置参数检测
        if($appid== "" || $mchid== "" || $key== ""){
            \App\error(1002,'微信H5支付未配置');
        }

        $noceStr = md5(rand(100,1000).time());//获取随机字符串

        $paramarr = array(
            "appid"       =>   $appid,
            "body"        =>    $body,
            "mch_id"      =>    $mchid,
            "nonce_str"   =>    $noceStr,
            "notify_url"  =>    $url,
            "out_trade_no"=>    $orderid,
            "total_fee"   =>    $money*100,
            "trade_type"  =>    "JSAPI",
            'openid'     =>     $openid,
        );

        $sign = self::sign($paramarr,$key);//生成签名
        $paramarr['sign'] = $sign;

        $result2 = self::unifiedorder($paramarr);

        $time2 = time();
        $prepayid = $result2['prepay_id'];
        $sign = "";
        $noceStr = md5(rand(100,1000).time());//获取随机字符串
        //二次验签
        $paramarr2 = array(
            "appId"     =>  $appid,
            "nonceStr"  =>  $noceStr, //微信官方推荐用官方接口返回的随机串, 用自己生成的也行
            "package"   =>  'prepay_id='.$prepayid,
            "signType"  =>  'MD5',
            "timeStamp" =>  ''.$time2
        );

        $paramarr2["sign"] = self::sign($paramarr2,$key);//生成签名

        return $paramarr2;
    }

    /*
     * 获取客户端IP
     */
    public static function getClientIp(){
        $ip = '';
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $ip_arr = explode(',', $ip);
        return $ip_arr[0];

    }

    /**
     * sign拼装获取
     */
    public static function sign($param,$key){
        $sign = "";
        ksort($param);
        foreach($param as $k => $v){
            $sign .= $k."=".$v."&";
        }
        $sign .= "key=".$key;
        $sign = strtoupper(md5($sign));
        return $sign;

    }
    /**
     * xml转为数组
     */
    public static function xmlToArray($xmlStr){
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

    public static function unifiedorder($paramarr){

        $paramXml = "<xml>";
        foreach($paramarr as $k => $v){
            $paramXml .= "<" . $k . ">" . $v . "</" . $k . ">";
        }
        $paramXml .= "</xml>";
        $ch = curl_init ();
        @curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        @curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
        @curl_setopt($ch, CURLOPT_URL, "https://api.mch.weixin.qq.com/pay/unifiedorder");
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        @curl_setopt($ch, CURLOPT_POST, 1);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $paramXml);
        @$resultXmlStr = curl_exec($ch);
        if(curl_errno($ch)){
            //print curl_error($ch);
            file_put_contents(API_ROOT.'/../data/log/mpPay_'.date('Y-m-d').'.txt',date('y-m-d H:i:s').' 提交参数信息 ch:'.json_encode(curl_error($ch))."\r\n",FILE_APPEND);
        }
        curl_close($ch);

        $result2 = self::xmlToArray($resultXmlStr);
        //file_put_contents('./zhifu.txt',date('Y-m-d H:i:s').' 提交参数信息 $result2:'.json_encode($result2)."\r\n",FILE_APPEND);
        if($result2['return_code']=='FAIL'){
            \App\error(1005,$result2['return_msg']);
        }

        if($result2['result_code']=='FAIL'){
            \App\error(1005,$result2['err_code_des']);
        }

        return $result2;
    }

    public static function refund($paytype,$outTradeNo, $price, $outRefundNo,$notify_url, $reason='')
    {
        $configpri = \App\getConfigPri();

        $appid = $configpri['wx_appid'] ?? '';
        $mchid = $configpri['wx_mchid'] ?? '';
        $key = $configpri['wx_key'] ?? '';

        if ($paytype==3) {
            $appid = $configpri['small_appid'] ?? '';
            $mchid = $configpri['small_mchid'] ?? '';
            $key = $configpri['small_key'] ?? '';
        }

        if ($paytype==4) {
            $appid = $configpri['pc_wx_appid'] ?? '';
            $mchid = $configpri['pc_wx_mchid'] ?? '';
            $key = $configpri['pc_wx_key'] ?? '';
        }

        if ($paytype==5) {
            $appid = $configpri['mp_appid'] ?? '';
            $mchid = $configpri['mp_mchid'] ?? '';
            $key = $configpri['mp_key'] ?? '';
        }

        //配置参数检测
        if ($appid == "" || $mchid == "" || $key == "") {
            return '微信未配置';
        }

        $noceStr = md5(rand(100, 1000) . time());//获取随机字符串
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


        if($reason!=''){
            $param['refund_desc']=$reason;
        }
        $sign = self::sign($param, $key);//生成签名
        $param['sign'] = $sign;

        $paramXlm = self::arrayToXml($param);

        //证书
        $keyPath = API_ROOT . "/../wanyue/wxpay/apiclient_key.pem";
        $certPath = API_ROOT . "/../wanyue/wxpay/apiclient_cert.pem";

//        $keyPath = get_upload_path($configpri['wx_key_file']);
//        $certPath = get_upload_path($configpri['wx_key_file']);

        if(!file_exists($keyPath) || !file_exists($certPath)){
            return "微信证书未配置,无法退款";
        }
        $requestUrl = "https://api.mch.weixin.qq.com/secapi/pay/refund";
        $result = \App\curl_post($requestUrl, $paramXlm, [], 0, $certPath, $keyPath);
        $resultArr = self::xmlToArray($result);
        if(!is_array($resultArr)){
            return "申请退款失败";
        }
        if(!array_key_exists('result_code',$resultArr)){
            return "申请退款失败";
        }
        if($resultArr['result_code'] != 'SUCCESS'){
            return $resultArr['err_code_des'] ?? "申请退款失败";
        }

        return 1;
    }
}
