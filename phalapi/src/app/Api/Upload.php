<?php

namespace App\Api;

use PhalApi\Api;

/**
 * 上传
 */
class Upload extends Api
{

  public function getRules()
  {
    return array();
  }

  /**
   * 获取七牛Token
   * @desc 用于获取七牛Token
   * @return int code 操作码，0表示成功，
   * @return array  info
   * @return string info[0].token 七牛Token
   * @return string msg 提示信息
   */
  public function getQiniuToken()
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());

    $uid = \App\checkNull($this->uid);
    $token = \App\checkNull($this->token);

    $checkToken = \App\checkToken($uid, $token);
    if ($checkToken == 700) {
      $rs['code'] = $checkToken;
      $rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
      return $rs;
    }

    $token = \PhalApi\DI()->qiniu->getToken();

    $token2 = \App\string_encryption($token);
    $info['token'] = $token2;
    $rs['info'][0] = $info;

    return $rs;
  }

  /**
   * 获取上传配置
   * @desc 用于获取上传配置
   * @return int code 操作码，0表示成功，
   * @return array  info
   * @return string info[0].uptype 上传方式 0本地 1七牛 2阿里
   * @return object info[0].qiniu 七牛上传信息
   * @return string info[0].qiniu.region 存储区域  z0华东 z1华北 z2华南 na0北美 as0东南亚 cn-east-2华东-浙江2
   * @return string info[0].qiniu.token 上传Token 需解密
   * @return object info[0].ali 阿里上传信息
   * @return string info[0].ali.token 上传Token 需解密
   * @return string info[0].ali.endpoint 地域节点
   * @return string info[0].ali.bucket 存储空间名称
   * @return object info[0].txcos 腾讯云上传信息
   * @return string info[0].txcos.appid 需解密
   * @return string info[0].txcos.region 地域节点
   * @return string info[0].txcos.bucket 存储空间名称
   * @return string msg 提示信息
   */
  public function getUpload()
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());

    $uid = \App\checkNull($this->uid);
    $token = \App\checkNull($this->token);

    $checkToken = \App\checkToken($uid, $token);
    if ($checkToken == 700) {
      $rs['code'] = $checkToken;
      $rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
      return $rs;
    }

    $qiniu = [
      'token' => '',
      'region' => '',
    ];

    $ali = [
      'token' => '',
      'region' => '',
      'bucket' => '',
    ];

    $txcos = [
      'appid' => '',
      'endpoint' => '',
      'bucket' => '',
      'sessionToken' => '',
      'tmpSecretId' => '',
      'tmpSecretKey' => '',
      'requestId' => '',
      'expiredTime' => '',
      'startTime' => '',
    ];

    $uptype = \PhalApi\DI()->config->get('app.uptype');
    $info = [
      'uptype' => $uptype,
    ];
    if ($uptype == 1) {

      $Qiniu_con = \PhalApi\DI()->config->get('app.Qiniu');

      $token = \PhalApi\DI()->qiniu->getToken();
      $token2 = \App\string_encryption($token);

      $qiniu['region'] = $Qiniu_con['region'];
      $qiniu['token'] = $token2;
    }

    if ($uptype == 2) {
      $Ali_con = \PhalApi\DI()->config->get('app.Aliyun');
      $endpoint = $Ali_con['endpoint'];
      $bucket = $Ali_con['bucket'];

      $ali['endpoint'] = $endpoint;
      $ali['bucket'] = $bucket;
    }

    if ($uptype == 3) {
      $Txcos_con = \PhalApi\DI()->config->get('app.Txcos');
      $appid = $Txcos_con['appid'];
      $region = $Txcos_con['region'];
      $bucket = $Txcos_con['bucket'];

      $txcos['appid'] = \App\string_encryption($appid);
      $txcos['region'] = $region;
      $txcos['bucket'] = $bucket;

      $sts = $this->getTxSts();

      $txcos = array_merge($txcos, $sts['info'][0]);
    }


    $info['qiniu'] = $qiniu;
    $info['ali'] = $ali;
    $info['txcos'] = $txcos;
    $rs['info'][0] = $info;

    return $rs;
  }

  /**
   * 阿里云上传sts信息
   * @desc 用于获取阿里云上传sts信息
   */
  public function getAliSts()
  {
    $Ali_con = \PhalApi\DI()->config->get('app.Aliyun');

    require_once API_ROOT . '/../sdk/alibabacloud/autoload.php';

    $accessKeyId = $Ali_con['accessKeyId'];
    $accessSecret = $Ali_con['accessKeySecret'];
    $endpoint = $Ali_con['endpoint'];
    $regionId = $Ali_con['regionId'];
    $bucket = $Ali_con['bucket'];
    $RoleArn = $Ali_con['roleArn'];
    $RoleSessionName = 'app';
    //构建一个阿里云客户端，用于发起请求。
    //构建阿里云客户端时需要设置AccessKey ID和AccessKey Secret。
    \AlibabaCloud\Client\AlibabaCloud::accessKeyClient($accessKeyId, $accessSecret)
      ->regionId($regionId)
      ->asDefaultClient();
    //设置参数，发起请求。
    try {
      $result = \AlibabaCloud\Client\AlibabaCloud::rpc()
        ->product('Sts')
        ->scheme('https') // https | http
        ->version('2015-04-01')
        ->action('AssumeRole')
        ->method('POST')
        ->host('sts.aliyuncs.com')
        ->options([
          'query' => [
            'RegionId' => $regionId,
            'RoleArn' => $RoleArn,
            'RoleSessionName' => $RoleSessionName,
          ],
        ])
        ->request();
      $result_a = $result->toArray();

      $rs = $result_a['Credentials'];
      $rs['StatusCode'] = 200;

      echo json_encode($rs);
      exit;

      //print_r($result->toArray());
    } catch (\AlibabaCloud\Client\Exception\ClientException $e) {
      //echo $e->getErrorMessage() . PHP_EOL;
      $rs = [
        'StatusCode' => 500,
        'ErrorCode' => $e->getErrorMessage(),
        'ErrorMessage' => $e->getErrorMessage(),
      ];
      echo json_encode($rs);
      exit;
    } catch (\AlibabaCloud\Client\Exception\ServerException $e) {
      //echo $e->getErrorMessage() . PHP_EOL;
      $rs = [
        'StatusCode' => 500,
        'ErrorCode' => $e->getErrorMessage(),
        'ErrorMessage' => $e->getErrorMessage(),
      ];
      echo json_encode($rs);
      exit;
    }
  }

  /**
   * 腾讯云上传sts信息
   * @desc 用于获取腾讯云上传sts信息
   */
  public function getTxSts()
  {

    $rs = array('code' => 0, 'msg' => '', 'info' => array());

    $Txcos_con = \PhalApi\DI()->config->get('app.Txcos');

    require_once API_ROOT . '/../sdk/tencentcloud/sts.php';

    $sts = new \STS();
    $config = array(
      'url' => 'https://sts.tencentcloudapi.com/',
      'domain' => 'sts.tencentcloudapi.com',
      'proxy' => '',
      'secretId' => $Txcos_con['secretId'], // 腾讯云存储secretid密钥
      'secretKey' => $Txcos_con['secretKey'], // 腾讯云存储secretkey
      'bucket' => $Txcos_con['bucket'], // bucket-appid
      'region' => $Txcos_con['region'], // 换成 bucket 所在地区 如ap-shanghai
      'durationSeconds' => 1800, // 密钥有效期
      'allowPrefix' => '*', // 这里改成允许的路径前缀，可以根据自己网站的用户登录状态判断允许上传的具体路径，例子： a.jpg 或者 a/* 或者 * (使用通配符*存在重大安全风险, 请谨慎评估使用)
      // 密钥的权限列表。简单上传和分片需要以下的权限，其他权限列表请看 https://cloud.tencent.com/document/product/436/31923
      'allowActions' => array(
        // 简单上传
        'name/cos:PutObject',
        'name/cos:PostObject',
        // 分片上传
        'name/cos:InitiateMultipartUpload',
        'name/cos:ListMultipartUploads',
        'name/cos:ListParts',
        'name/cos:UploadPart',
        'name/cos:CompleteMultipartUpload'
      )
    );

    // 获取临时密钥，计算签名
    $tempKeys = $sts->getTempKeys($config);

    $info['sessionToken'] = $tempKeys['credentials']['sessionToken'] ?? '';
    $info['tmpSecretId'] = $tempKeys['credentials']['tmpSecretId'] ?? '';
    $info['tmpSecretKey'] = $tempKeys['credentials']['tmpSecretKey'] ?? '';
    $info['requestId'] = $tempKeys['requestId'] ?? '';
    $info['expiredTime'] = (string)$tempKeys['expiredTime'] ?? '';
    $info['startTime'] = (string)$tempKeys['startTime'] ?? '';

    $rs['info'][0] = $info;

    return $rs;
  }


  /**
   * 上传
   * @desc 用于上传文件
   */
  public function upload()
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());

    $uid = \App\checkNull($this->uid);
    $token = \App\checkNull($this->token);
    $checkToken = \App\checkToken($uid, $token);
    if ($checkToken == 700) {
      $rs['code'] = $checkToken;
      $rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
      return $rs;
    }

    $file = $_FILES["file"] ?? [];
    if (!$file) {
      $rs['code'] = 1001;
      $rs['msg'] = \PhalApi\T('请上传文件');
      return $rs;
    }

    if ($file["error"] > 0) {
      $rs['code'] = 1002;
      $rs['msg'] = '上传错误';
      return $rs;
    }

    $uptype = \PhalApi\DI()->config->get('app.uptype');
    $url = '';
    if ($uptype == 0) {
    }
    if ($uptype == 1) {
    }
    if ($uptype == 2) {
      $url = $this->upload_oss($file);
    }
    if ($uptype == 3) {
      $url = $this->upload_cos($file);
    }
    if ($url == '') {
      $rs['code'] = 1003;
      $rs['msg'] = \PhalApi\T('请上传文件');
      return $rs;
    }

    $url_all = \App\get_upload_path($url);

    $info = [
      'url' => $url,
      'url_all' => $url_all,
    ];

    $rs['info'][0] = $info;

    return $rs;
  }

  protected function upload_oss($file)
  {
    $Ali_con = \PhalApi\DI()->config->get('app.Aliyun');

    require_once API_ROOT . '/../sdk/OSS/autoload.php';

    // 阿里云主账号AccessKey拥有所有API的访问权限，风险很高。强烈建议您创建并使用RAM账号进行API访问或日常运维，请登录RAM控制台创建RAM账号。
    $accessKeyId = $Ali_con['accessKeyId'];
    $accessKeySecret = $Ali_con['accessKeySecret'];
    // Endpoint以杭州为例，其它Region请按实际情况填写。
    $endpoint = $Ali_con['endpoint'];
    // 设置存储空间名称。
    $bucket = $Ali_con['bucket'];
    // 设置文件名称。
    // 上传到七牛后保存的文件名
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $key = date('Ymd') . '/' . uniqid() . '.' . $ext;
    $object = $key;

    // <yourLocalFile>由本地文件路径加文件名包括后缀组成，例如/users/local/myfile.txt。
    $filePath = $file['tmp_name'];

    try {
      $ossClient = new \OSS\OssClient($accessKeyId, $accessKeySecret, $endpoint);

      $ossClient->uploadFile($bucket, $object, $filePath);
    } catch (\OSS\Core\OssException $e) {
      //            printf(__FUNCTION__ . ": FAILED\n");
      //            printf($e->getMessage() . "\n");
      return '';
    }

    return $object;
  }

  protected function upload_cos($file)
  {
    $Txcos = \PhalApi\DI()->config->get('app.Txcos');

    require_once API_ROOT . '/../sdk/COS/vendor/autoload.php';

    // SECRETID和SECRETKEY请登录访问管理控制台进行查看和管理
    $secretId = $Txcos['secretId']; //替换为用户的 secretId，请登录访问管理控制台进行查看和管理，https://console.cloud.tencent.com/cam/capi
    $secretKey = $Txcos['secretKey']; //替换为用户的 secretKey，请登录访问管理控制台进行查看和管理，https://console.cloud.tencent.com/cam/capi
    $region = $Txcos['region']; //替换为用户的 region，已创建桶归属的region可以在控制台查看，https://console.cloud.tencent.com/cos5/bucket
    $bucket = $Txcos['bucket']; //存储桶名称 格式：BucketName-APPID

    $cosClient = new \Qcloud\Cos\Client(
      array(
        'region' => $region,
        'schema' => 'https', //协议头部，默认为http
        'credentials' => array(
          'secretId' => $secretId,
          'secretKey' => $secretKey
        )
      )
    );

    // 上传到七牛后保存的文件名
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $key = date('Ymd') . '/' . uniqid() . '.' . $ext;

    $file2 = fopen($file['tmp_name'], "rb");
    try {
      $result = $cosClient->putObject(array(
        'Bucket' => $bucket,
        'Key' => $key,
        'Body' => $file2
      ));
      //print_r($result);
    } catch (\Exception $e) {
      //echo "$e\n";
      return '';
    }

    return $key;
  }
}
