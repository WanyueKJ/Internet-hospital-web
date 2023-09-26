<?php

namespace App;

/* curl get请求 */

use Merchant\Model\MerchantStore as MerchantStoreModel;
use Merchant\Model\Users as UsersModel;

function curl_get($url, $headers = [], $type = 0)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_NOBODY, false);
    if ($headers) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    curl_setopt($curl, CURLOPT_ENCODING, '');

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 跳过证书检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);  // 从证书中检查SSL加密算法是否存在
    $return_str = curl_exec($curl);

    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($type == 1) {
        return ['code' => $httpCode, 'res' => $return_str];
    }

    return $return_str;
}

/* curl POST 请求 */
function curl_post($url, $curlPost = '', $headers = [], $type = 0)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_NOBODY, false);
    curl_setopt($curl, CURLOPT_POST, true);
    if ($curlPost) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    }

    if ($headers) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 跳过证书检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);  // 从证书中检查SSL加密算法是否存在

    $return_str = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($type == 1) {
        return ['code' => $httpCode, 'res' => $return_str];
    }
    return $return_str;
}


/* curl POST 请求 */
function curl_post2($url, $curlPost = '', $headers = [], $apiclientCert = "", $apiclientKey = "")
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_NOBODY, false);
    curl_setopt($curl, CURLOPT_POST, true);
    if ($curlPost) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    }

    if ($headers) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    if ($apiclientCert) {
        curl_setopt($curl, CURLOPT_SSLCERT, $apiclientCert);
    }
    if ($apiclientKey) {
        curl_setopt($curl, CURLOPT_SSLKEY, $apiclientKey);
    }

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 跳过证书检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);  // 从证书中检查SSL加密算法是否存在

    $return_str = curl_exec($curl);
    return $return_str;
}

/* 去除NULL 判断空处理 主要针对字符串类型*/
function checkNull($checkstr)
{
    $checkstr = trim($checkstr);
    //$checkstr=urldecode($checkstr);
    $checkstr = html_entity_decode($checkstr);

    if (strstr($checkstr, 'null') || (!$checkstr && $checkstr != 0)) {
        $str = '';
    } else {
        $str = $checkstr;
    }
    return $str;
}

/* 去除emoji表情 */
function filterEmoji($str)
{
    $str = preg_replace_callback(
        '/./u',
        function (array $match) {
            return strlen($match[0]) >= 4 ? '' : $match[0];
        },
        $str);
    return $str;
}

/* 校验签名 */
function checkSign($data, $sign)
{
    // return 1;
    if ($sign == '') {
        return 0;
    }
    $key = \PhalApi\DI()->config->get('app.sign_key');
    $str = '';
    ksort($data);
    foreach ($data as $k => $v) {
        $str .= $k . '=' . $v . '&';
    }
    $str .= $key;
    $newsign = md5($str);
    if ($sign == $newsign) {
        return 1;
    }
    return 0;
}

/* 检验手机号 */
function checkMobile($mobile)
{
    $ismobile = preg_match("/^1[3|4|5|6|7|8|9]\d{9}$/", $mobile);
    if ($ismobile) {
        return 1;
    }

    return 0;

}

/* 校验邮箱 */
function checkEmail($email)
{
    $preg = '/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/';
    $isok = preg_match($preg, $email);
    if ($isok) {
        return 1;
    }
    return 0;
}

/* 校验密码 */
function checkPass($pass)
{
    /* 必须包含字母、数字 */
    $preg = '/^(?=.*[A-Za-z])(?=.*[0-9])[a-zA-Z0-9~!@&%#_]{6,20}$/';
    $isok = preg_match($preg, $pass);
    if ($isok) {
        return 1;
    }
    return 0;
}

/* 密码加密 */
function setPass($pass)
{

    $authcode = \PhalApi\DI()->config->get('app.authcode');
    $pass = "###" . md5(md5($authcode . $pass));
    return $pass;
}

/* 随机数 */
function random($length = 6, $numeric = 1)
{
    PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
    if ($numeric == 1) {
        $hash = sprintf('%0' . $length . 'd', mt_rand(0, pow(10, $length) - 1));
    } else {
        $hash = '';
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
        if ($numeric == 2) {
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        }
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
    }
    return $hash;
}

/* 发送验证码 */
function sendCode($account, $code)
{
    $rs = array('code' => 1001, 'msg' => \PhalApi\T('发送失败'));
    $config = getConfigPri();

    if (!$config['sendcode_switch']) {
        $rs['code'] = 667;
        $rs['msg'] = '123456';
        return $rs;
    }

    $res = sendCodeByTx($account, $code);

    return $res;
}

/* 公共配置 */
function getConfigPub()
{
    $key = 'getConfigPub';
    if (isset($GLOBALS[$key])) {
        return $GLOBALS[$key];
    }
    $config = getcaches($key);
    if (!$config) {
        $config = \PhalApi\DI()->notorm->option
            ->select('option_value')
            ->where("option_name='site_info'")
            ->fetchOne();
        $config = json_decode($config['option_value'], true);
        setcaches($key, $config);
    }

    $GLOBALS[$key] = $config;

    return $config;
}

/* 公共配置 */
function getUploadSetting()
{
    $key = 'upload_setting';
    if (isset($GLOBALS[$key])) {
        return $GLOBALS[$key];
    }
    $config = getcaches($key);
    if (!$config) {
        $config = \PhalApi\DI()->notorm->option
            ->select('option_value')
            ->where("option_name='upload_setting'")
            ->fetchOne();
        $config = json_decode($config, true);
        setcaches($key, $config);
    }

    $GLOBALS[$key] = $config;

    return $config;
}

/* 私密配置 */
function getConfigPri()
{
    $key = 'getConfigPri';
    if (isset($GLOBALS[$key])) {
        return $GLOBALS[$key];
    }
    $config = getcaches($key);
    if (!$config) {
        $config = \PhalApi\DI()->notorm->option
            ->select('option_value')
            ->where("option_name='configpri'")
            ->fetchOne();
        $config = json_decode($config['option_value'], true);
        setcaches($key, $config);
    }

    $GLOBALS[$key] = $config;

    return $config;
}

/**
 * 返回带协议的域名
 */
function get_host()
{
    $config = getConfigPub();
    return $config['site_host'];
}

/**
 * 转化数据库保存的文件路径，为可以访问的url
 */
function get_upload_path($file)
{
    if ($file == '') {
        return '';
    }
    if (strpos($file, "http") === 0) {
        $filepath = $file;
    } else if (strpos($file, "/") === 0) {
        $filepath = get_host() . $file;
    } else {
        $uptype = \PhalApi\DI()->config->get('app.uptype');
        if ($uptype == 3) {
            $space_host = \PhalApi\DI()->config->get('app.Txcos.host');
        } elseif ($uptype == 2) {
            $space_host = \PhalApi\DI()->config->get('app.Aliyun.host');
        } else if ($uptype == 1) {
            /* 七牛上传 */
            $space_host = \PhalApi\DI()->config->get('app.Qiniu.space_host');
        } else {
            /* 本地 上传 */
            $space_host = get_host() . '/upload';
        }
        $filepath = $space_host . "/" . $file;
    }
    $filepath = urldecode($filepath);
    return html_entity_decode($filepath);
}

/* 判断token */
function checkToken($uid, $token)
{
    if ($uid < 1 || $token == '') {
        return 700;
    }

    $key = "token_" . $uid;
    $userinfo = getcaches($key);
    if (!$userinfo) {
        $userinfo = getcaches("merchant_token_" . $uid);
    }

    if (!$userinfo) {
        $userinfo = \PhalApi\DI()->notorm->users_token
            ->select('token,expire_time')
            ->where('user_id = ? ', $uid)
            ->fetchOne();
        if ($userinfo) {
            setcaches($key, $userinfo);

            $merchant = getcaches("merchant_token_" . $uid);
            if ($merchant) {
                $merchant['token'] = $userinfo['token'];
                $merchant['expire_time'] = $userinfo['expire_time'];
                setcaches("merchant_token_" . $uid, $merchant);
            }

        }
    }

    if (!$userinfo || $userinfo['token'] != $token || $userinfo['expire_time'] < time()) {
        return 700;
    }
    return 0;
}

/* 判断 商户 token */
function checkMerchantToken($uid, $token)
{
    if ($uid < 1 || $token == '') {
        return 700;
    }
    $key = "merchant_token_" . $uid;
    $userinfo = getcaches($key);
    if (!$userinfo) {
        $userinfo = \PhalApi\DI()->notorm->users_token
            ->select('token,expire_time')
            ->where('user_id = ? ', $uid)
            ->fetchOne();

        $UsersModel = new UsersModel();
        $accountInfo = $UsersModel->getOne(['id = ?' => $uid], 'id as uid,store_id,user_status,user_nickname,type,mobile');
        if (!$accountInfo) {
            return 700;
        }

        if ($accountInfo['type'] != 1) {
            return 700;
        }

        $MerchantStoreModel = new MerchantStoreModel();
        $MerchantStore = $MerchantStoreModel->getOne(['id = ?' => $accountInfo['store_id']], 'id,name,thumb,operating_state');

        $token_info = array(
            'user_id' => $uid,
            'token' => $userinfo['token'],
            'expire_time' => $userinfo['expire_time'],
            'store' => $MerchantStore,
        );
        setcaches($key, $token_info);
    }
    if (!$userinfo || $userinfo['token'] != $token || $userinfo['expire_time'] < time()) {
        return 700;
    }
    return 0;

}

/* 用户基本信息 */
function getUserInfo($uid, $type = 0)
{
    $info = getcaches("userinfo_" . $uid);
    if (!$info) {
        $info = \PhalApi\DI()->notorm->users
            ->select('id,user_nickname,avatar,avatar_thumb,sex,signature,birthday')
            ->where('id=?', $uid)
            ->fetchOne();
        if (!$info) {
            $info['id'] = $uid;
            $info['user_nickname'] = \PhalApi\T('用户不存在');
            $info['avatar'] = '/default.png';
            $info['avatar_thumb'] = '/default_thumb.png';
            $info['sex'] = '0';
            $info['signature'] = '';
            $info['birthday'] = '0';
            $info['isdel'] = '1';
        }
        if ($info) {
            setcaches("userinfo_" . $uid, $info);
        }
    }

    if ($type == 1) {
        if (isset($info['isdel']) && $info['isdel'] == 1) {
            return [];
        }
    }

    if ($info) {
        $info = handleUser($info);
    }

    return $info;
}

/* 处理用户信息 */
function handleUser($info)
{

    $info['avatar'] = get_upload_path($info['avatar']);
    $info['avatar_thumb'] = get_upload_path($info['avatar_thumb']);

    unset($info['birthday']);

    return $info;
}

/* 判断token */
function checkRiderToken($uid, $token)
{
    if ($uid < 1 || $token == '') {
        return 700;
    }

    $key = "rider_token_" . $uid;
    $userinfo = getcaches($key);
    if (!$userinfo) {
        $userinfo = \PhalApi\DI()->notorm->rider_token
            ->select('token,expire_time')
            ->where('user_id = ? ', $uid)
            ->fetchOne();
        if ($userinfo) {
            setcaches($key, $userinfo);
        }
    }

    if (!$userinfo || $userinfo['token'] != $token || $userinfo['expire_time'] < time()) {
        return 700;
    }
    return 0;

}

/* 用户基本信息 */
function getRiderInfo($uid, $type = 0)
{
    $key = "rider_userinfo_" . $uid;
    $info = getcaches($key);
    if (!$info) {
        $info = \PhalApi\DI()->notorm->rider
            ->select('id,user_nickname,avatar,avatar_thumb,sex,signature,birthday,mobile')
            ->where('id=?', $uid)
            ->fetchOne();
        if (!$info) {
            $info['id'] = $uid;
            $info['user_nickname'] = \PhalApi\T('用户不存在');
            $info['avatar'] = '/default.png';
            $info['avatar_thumb'] = '/default_thumb.png';
            $info['sex'] = '0';
            $info['signature'] = '';
            $info['birthday'] = '0';
            $info['isdel'] = '1';
            $info['mobile'] = '';
        }
        if ($info) {
            setcaches($key, $info);
        }
    }

    if ($type == 1) {
        if (isset($info['isdel']) && $info['isdel'] == 1) {
            return [];
        }
    }

    if ($info) {
        $info = handleUser($info);
    }

    return $info;
}

/* 年龄计算 */
function getAge($time = 0)
{
    if ($time <= 0) {
        return '';
    }
    $nowtime = time();
    $y_n = date('Y', $nowtime);
    $y_b = date('Y', $time);

    $age = $y_n - $y_b;

    return (string)$age;
}


/* 字符串加密 */
function string_encryption($code)
{
    $str = '1ecxXyLRB.COdrAi:q09Z62ash-QGn8VFNIlb=fM/D74WjS_EUzYuw?HmTPvkJ3otK5gp&*+%';
    $strl = strlen($str);

    $len = strlen($code);

    $newCode = '';
    for ($i = 0; $i < $len; $i++) {
        for ($j = 0; $j < $strl; $j++) {
            if ($str[$j] == $code[$i]) {
                if (($j + 1) == $strl) {
                    $newCode .= $str[0];
                } else {
                    $newCode .= $str[$j + 1];
                }
            }
        }
    }
    return $newCode;
}

/* 字符串解密 */
function string_decrypt($code)
{
    $str = '1ecxXyLRB.COdrAi:q09Z62ash-QGn8VFNIlb=fM/D74WjS_EUzYuw?HmTPvkJ3otK5gp&*+%';
    $strl = strlen($str);

    $len = strlen($code);

    $newCode = '';
    for ($i = 0; $i < $len; $i++) {
        for ($j = 0; $j < $strl; $j++) {
            if ($str[$j] == $code[$i]) {
                if ($j - 1 < 0) {
                    $newCode .= $str[$strl - 1];
                } else {
                    $newCode .= $str[$j - 1];
                }
            }
        }
    }
    return $newCode;
}


/* 时长处理 */
function handellength($cha, $type = 0)
{
    $iz = floor($cha / 60);
    $hz = floor($iz / 60);
    $dz = floor($hz / 24);
    /* 秒 */
    $s = $cha % 60;
    /* 分 */
    $i = floor($iz % 60);
    /* 时 */
    $h = floor($hz % 24);
    /* 天 */

    if ($type == 1) {
        if ($s < 10) {
            $s = '0' . $s;
        }

        if ($iz < 10) {
            $iz = '0' . $iz;
        }

        return \PhalApi\T('{i}:{s}', ['i' => $iz, 's' => $s]);
    }
    $str = '';
    if ($dz > 0) {
        $str .= \PhalApi\T('{d}天', ['d' => $dz]);
    }
    if ($h > 0) {
        $str .= \PhalApi\T('{h}小时', ['h' => $h]);
    }
    if ($i > 0) {
        $str .= \PhalApi\T('{i}分钟', ['i' => $i]);
    }
    if ($s > 0) {
        $str .= \PhalApi\T('{s}秒', ['s' => $s]);
    }
    return $str;
}

/* 时长处理 */
function handellength2($cha, $type = 0)
{
    $iz = floor($cha / 60);
    $hz = floor($iz / 60);
    $dz = floor($hz / 24);
    /* 秒 */
    $s = $cha % 60;
    /* 分 */
    $i = floor($iz % 60);
    /* 时 */
    $h = floor($hz % 24);
    /* 天 */

    if ($type == 1) {
        if ($s < 10) {
            $s = '0' . $s;
        }

        if ($iz < 10) {
            $iz = '0' . $iz;
        }

        return \PhalApi\T('{i}:{s}', ['i' => $iz, 's' => $s]);
    }
    $str = '';
    if ($dz > 0) {
        $str .= \PhalApi\T('{d}天', ['d' => $dz]);
        return $str;
    }
    if ($h > 0) {
        $str .= \PhalApi\T('{h}小时', ['h' => $h]);
        return $str;
    }
    if ($i > 0) {
        $str .= \PhalApi\T('{i}分钟', ['i' => $i]);
        return $str;
    }
    if ($s > 0) {
        $str .= \PhalApi\T('{s}秒', ['s' => $s]);
        return $str;
    }
    return $str;
}

/* 毫秒时间戳 */
function getMillisecond()
{
    list($msec, $sec) = explode(' ', microtime());
    $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    return substr($msectime, 0, 13);
}

/* 腾讯云短信验证码 */
function sendCodeByTx($mobile, $code, $country_code = '+86')
{
    $rs = array('code' => 0, 'msg' => '', 'info' => array());

    $config = getConfigPri();

    require_once API_ROOT . '/../sdk/tencentcloud/TCloudAutoLoader.php';

    $tx_api_secretid = $config['tx_api_secretid'];
    $tx_api_secretkey = $config['tx_api_secretkey'];
    $tx_sms_sdkappid = $config['tx_sms_sdkappid'];
    $tx_sms_sign = $config['tx_sms_sign'];
    $tx_sms_tempid = $config['tx_sms_tempid'];

    $mobile = $country_code . $mobile;
    $phonenums = [$mobile];

    $params = [$code];

    try {
        /* 必要步骤：
         * 实例化一个认证对象，入参需要传入腾讯云账户密钥对secretId，secretKey。
         * 这里采用的是从环境变量读取的方式，需要在环境变量中先设置这两个值。
         * 你也可以直接在代码中写死密钥对，但是小心不要将代码复制、上传或者分享给他人，
         * 以免泄露密钥对危及你的财产安全。
         * CAM密匙查询: https://console.cloud.tencent.com/cam/capi*/

        $cred = new \TencentCloud\Common\Credential($tx_api_secretid, $tx_api_secretkey);

        // 实例化一个http选项，可选的，没有特殊需求可以跳过
        // $httpProfile = new \TencentCloud\Common\Profile\HttpProfile();
        // $httpProfile->setReqMethod("GET");  // post请求(默认为post请求)
        // $httpProfile->setReqTimeout(30);    // 请求超时时间，单位为秒(默认60秒)
        // $httpProfile->setEndpoint("sms.tencentcloudapi.com");  // 指定接入地域域名(默认就近接入)

        // 实例化一个client选项，可选的，没有特殊需求可以跳过
        // $clientProfile = new \TencentCloud\Common\Profile\ClientProfile();
        // $clientProfile->setSignMethod("TC3-HMAC-SHA256");  // 指定签名算法(默认为HmacSHA256)
        // $clientProfile->setHttpProfile($httpProfile);

        // 实例化要请求产品(以sms为例)的client对象,clientProfile是可选的
        // $client = new SmsClient($cred, "ap-shanghai", $clientProfile);
        $client = new \TencentCloud\Sms\V20190711\SmsClient($cred, "ap-shanghai");

        // 实例化一个 sms 发送短信请求对象,每个接口都会对应一个request对象。
        $req = new \TencentCloud\Sms\V20190711\Models\SendSmsRequest();

        /* 填充请求参数,这里request对象的成员变量即对应接口的入参
         * 你可以通过官网接口文档或跳转到request对象的定义处查看请求参数的定义
         * 基本类型的设置:
         * 帮助链接：
         * 短信控制台: https://console.cloud.tencent.com/sms/smslist
         * sms helper: https://cloud.tencent.com/document/product/382/3773 */

        /* 短信应用ID: 短信SdkAppid在 [短信控制台] 添加应用后生成的实际SdkAppid，示例如1400006666 */
        $req->SmsSdkAppid = $tx_sms_sdkappid;
        /* 短信签名内容: 使用 UTF-8 编码，必须填写已审核通过的签名，签名信息可登录 [短信控制台] 查看 */
        $req->Sign = $tx_sms_sign;
        /* 短信码号扩展号: 默认未开通，如需开通请联系 [sms helper] */
        $req->ExtendCode = "0";
        /* 下发手机号码，采用 e.164 标准，+[国家或地区码][手机号]
         * 示例如：+8613711112222， 其中前面有一个+号 ，86为国家码，13711112222为手机号，最多不要超过200个手机号*/
        $req->PhoneNumberSet = $phonenums;
        /* 国际/港澳台短信 senderid: 国内短信填空，默认未开通，如需开通请联系 [sms helper] */
        $req->SenderId = "";
        /* 用户的 session 内容: 可以携带用户侧 ID 等上下文信息，server 会原样返回 */
        $req->SessionContext = "";
        /* 模板 ID: 必须填写已审核通过的模板 ID。模板ID可登录 [短信控制台] 查看 */
        $req->TemplateID = $tx_sms_tempid;
        /* 模板参数: 若无模板参数，则设置为空*/
        $req->TemplateParamSet = $params;

        // 通过client对象调用DescribeInstances方法发起请求。注意请求方法名与请求对象是对应的
        // 返回的resp是一个DescribeInstancesResponse类的实例，与请求对象对应
        $resp = $client->SendSms($req);

        // 输出json格式的字符串回包
        $res = $resp->toJsonString();

        //{"SendStatusSet":[{"SerialNo":"2019:6180501101329406318","PhoneNumber":"+8613053838131","Fee":1,"SessionContext":"","Code":"Ok","Message":"send success"}],"RequestId":"69a550c3-74e9-4be7-b5bb-5856b7c36daa"}

        $res_a = json_decode($res, true);
        $nums = 0;
        $nums_e = 0;
        foreach ($res_a['SendStatusSet'] as $k => $v) {
            if ($v['Code'] != 'Ok') {
                $nums_e++;
            }
            $nums++;
        }
        // print_r($res);

        // file_put_contents(API_ROOT.'/../data/log/sendCode_tx_api_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 res:'.json_encode($res)."\r\n",FILE_APPEND);

        if ($nums == $nums_e) {
            $rs['code'] = 1002;
            //$rs['msg']=$gets['SubmitResult']['msg'];
            $rs['msg'] = \PhalApi\T("发送失败");
            return $rs;
        }

        // 也可以取出单个值。
        // 你可以通过官网接口文档或跳转到response对象的定义处查看返回字段的定义
        // print_r($resp->TotalCount);
    } catch (\TencentCloud\Common\Exception\TencentCloudSDKException $e) {
        // echo $e;
        file_put_contents(API_ROOT . '/../data/log/sendCode_tx_api_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . ' 提交参数信息 e:' . json_encode($e) . "\r\n", FILE_APPEND);

        $rs['code'] = 1002;
        //$rs['msg']=$gets['SubmitResult']['msg'];
        $rs['msg'] = \PhalApi\T("发送失败");
        return $rs;
    }


    return $rs;
}

/* []字符串拆分
*  string $str  [1],[2]
*/
function handelSetToArr($str)
{
    $list = [];
    if ($str == '') {
        return $list;
    }
    $list = explode(',', $str);
    foreach ($list as $k => $v) {
        $v = str_replace('[', '', $v);
        $v = str_replace(']', '', $v);
        $list[$k] = $v;
    }
    $list = array_values($list);

    return $list;
}

/* []字符串生成
*  array $arr [1,2,3]
*/
function handelSetToStr($arr)
{
    $str = '';
    if (!$arr) {
        return $str;
    }

    foreach ($arr as $k => $v) {
        $v = '[' . $v . ']';
        $arr[$k] = $v;
    }
    $str = implode(',', $arr);

    return $str;
}

/* 二维码生成
    $filepath  生成的二维码图片地址
    $url       二维码包含信息
    $size       二维码大小  $url 内容的多少会影响 size=1 时的基数大小 内容越多图片越大 拼接图片时 注意处理缩放
    $level      容错等级  L M Q H
*/
function qcodeCreate($filepath, $url, $size = 10, $level = 'L')
{
    require_once API_ROOT . '/../sdk/phpqrcode/phpqrcode.php';
    //生成二维码图片
    \QRcode::png($url, $filepath, $level, $size, 2);

    return $filepath;
}

function handelList($list, $pid = 0)
{
    $rs = [];
    foreach ($list as $k => $v) {
        if ($v['pid'] != $pid) {
            continue;
        }

        unset($list[$k]);
        $v['list'] = handelList($list, $v['id']);
        $rs[] = $v;
    }

    return $rs;
}


/**
 * 根据两点间的经纬度计算距离
 * @param $lng1 地点1经度
 * @param $lat1 地点1维度
 * @param $lng2 地点2经度
 * @param $lat2 地点2维度
 * @return int 单位千米
 */
function getDistance($lng1, $lat1, $lng2, $lat2)
{
    //将角度转为狐度
    $radLat1 = deg2rad($lat1);//deg2rad()函数将角度转换为弧度
    $radLat2 = deg2rad($lat2);

    $radLng1 = deg2rad($lng1);
    $radLng2 = deg2rad($lng2);
    $a = $radLat1 - $radLat2;
    $b = $radLng1 - $radLng2;
    $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.138;
    //返回值距离单位:千米
    return $s;
}

/* 星级 */
function handleStar($stars = 0, $comments = 0)
{
    if (!$stars || !$comments) {
        return '5.0';
    }

    $level = floor($stars / $comments * 10) * 0.1;

    return (string)$level;
}

/* 处理上架时间 用于显示 */
function handelsvctm($svctm)
{
    $nowtime = time();
    $today_start = strtotime(date('Ymd', $nowtime));
    $svctm_start = strtotime(date('Ymd', $svctm));

    if ($today_start < $svctm_start) {
        $length = ($svctm_start - $today_start) / (60 * 60 * 24);

        $hs = date('H:i', $svctm);
        if ($length == 0) {
            return \PhalApi\T('今天') . ' ' . $hs;
        }

        if ($length == 1) {
            return \PhalApi\T('明天') . ' ' . $hs;
        }

        if ($length == 2) {
            return \PhalApi\T('后天') . ' ' . $hs;
        }

        return date("m-d", $svctm) . ' ' . $hs;

    }

    $length = ($today_start - $svctm_start) / (60 * 60 * 24);

    $hs = date('H:i', $svctm);
    if ($length == 0) {
        return \PhalApi\T('今天') . ' ' . $hs;
    }

    return date("m-d H:i", $svctm);
}


/**
 * 时间人性化展示
 * @param int $time 相对于对比的时间
 * @param int $baseTime 对比的时间
 * @return string
 */
function timeFormatting(int $time, int $baseTime)
{
     $difference = $time - $baseTime;
    $str = $difference > 0 ? \PhalApi\T('天后') : \PhalApi\T("天前");
    $abs = abs($difference);
    $daySeconds = 86400;
    $divide = floor(round($abs / $daySeconds, 1));
    if ($divide == 0) return \PhalApi\T("当天");

    $date = $divide . $str;
    return $date;

}

/**
 * 分级排序
 * @param $data
 * @param $pid
 * @param $field
 * @param $pk
 * @param $html
 * @param $level
 * @param $clear
 * @return array
 */
function sort_list_tier($data, $pid = 0, $field = 'pid', $pk = 'id', $html = '|--', $level = 1, $clear = true)
{
    static $list = [];
    if ($clear) $list = [];
    foreach ($data as $k => $res) {
        if ($res[$field] == $pid) {
            $res['html'] = str_repeat($html, $level);
            $list[] = $res;
            unset($data[$k]);
            sort_list_tier($data, $res[$pk], $field, $pk, $html, $level + 1, false);
        }
    }
    return $list;
}

/**
 * 格式化Tree
 * @param array $data
 * @param string $childrenname
 * @param string $keyName
 * @param string $pidName
 * @return array
 */
function get_tree_children(array $data, string $childrenname = 'children', string $keyName = 'id', string $pidName = 'pid')
{
    $list = array();
    foreach ($data as $value) {
        $list[$value[$keyName]] = $value;
    }
    static $tree = array(); //格式化好的树
    foreach ($list as $key => $item) {
        if (isset($list[$item[$pidName]])) {
            $list[$item[$pidName]][$childrenname][] = &$list[$item[$keyName]];
        } else {
            $tree[] = &$list[$item[$keyName]];
        }
    }

    return $tree;
}


/* 腾讯IM签名-HMAC-SHA256 */
function txim_setSig($id)
{
    $getConfigPub = \App\getConfigPri();
    $appid = $getConfigPub['im_sdk_app_id'];
    $key = $getConfigPub['im_key'];
    if ($appid == '' || $key == '') {
        return '';
    }
    $path = API_ROOT . '/../sdk/txim/';
    require_once($path . "TLSSigAPIv2.php");

    $api = new \Tencent\TLSSigAPIv2($appid, $key);
    $sig = $api->genUserSig($id);

    return $sig;
}
