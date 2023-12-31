<?php
/*
 * Copyright (c) 2017-2018 THL A29 Limited, a Tencent company. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace TencentCloud\Live\V20180801\Models;
use TencentCloud\Common\AbstractModel;

/**
 * ModifyLiveCert请求参数结构体
 *
 * @method string getCertId() 获取证书Id。
 * @method void setCertId(string $CertId) 设置证书Id。
 * @method integer getCertType() 获取证书类型。0-用户添加证书；1-腾讯云托管证书。
 * @method void setCertType(integer $CertType) 设置证书类型。0-用户添加证书；1-腾讯云托管证书。
 * @method string getCertName() 获取证书名称。
 * @method void setCertName(string $CertName) 设置证书名称。
 * @method string getHttpsCrt() 获取证书内容，即公钥。
 * @method void setHttpsCrt(string $HttpsCrt) 设置证书内容，即公钥。
 * @method string getHttpsKey() 获取私钥。
 * @method void setHttpsKey(string $HttpsKey) 设置私钥。
 * @method string getDescription() 获取描述信息。
 * @method void setDescription(string $Description) 设置描述信息。
 */
class ModifyLiveCertRequest extends AbstractModel
{
    /**
     * @var string 证书Id。
     */
    public $CertId;

    /**
     * @var integer 证书类型。0-用户添加证书；1-腾讯云托管证书。
     */
    public $CertType;

    /**
     * @var string 证书名称。
     */
    public $CertName;

    /**
     * @var string 证书内容，即公钥。
     */
    public $HttpsCrt;

    /**
     * @var string 私钥。
     */
    public $HttpsKey;

    /**
     * @var string 描述信息。
     */
    public $Description;

    /**
     * @param string $CertId 证书Id。
     * @param integer $CertType 证书类型。0-用户添加证书；1-腾讯云托管证书。
     * @param string $CertName 证书名称。
     * @param string $HttpsCrt 证书内容，即公钥。
     * @param string $HttpsKey 私钥。
     * @param string $Description 描述信息。
     */
    function __construct()
    {

    }

    /**
     * For internal only. DO NOT USE IT.
     */
    public function deserialize($param)
    {
        if ($param === null) {
            return;
        }
        if (array_key_exists("CertId",$param) and $param["CertId"] !== null) {
            $this->CertId = $param["CertId"];
        }

        if (array_key_exists("CertType",$param) and $param["CertType"] !== null) {
            $this->CertType = $param["CertType"];
        }

        if (array_key_exists("CertName",$param) and $param["CertName"] !== null) {
            $this->CertName = $param["CertName"];
        }

        if (array_key_exists("HttpsCrt",$param) and $param["HttpsCrt"] !== null) {
            $this->HttpsCrt = $param["HttpsCrt"];
        }

        if (array_key_exists("HttpsKey",$param) and $param["HttpsKey"] !== null) {
            $this->HttpsKey = $param["HttpsKey"];
        }

        if (array_key_exists("Description",$param) and $param["Description"] !== null) {
            $this->Description = $param["Description"];
        }
    }
}
