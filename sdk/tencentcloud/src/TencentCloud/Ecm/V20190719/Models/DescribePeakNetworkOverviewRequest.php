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
namespace TencentCloud\Ecm\V20190719\Models;
use TencentCloud\Common\AbstractModel;

/**
 * DescribePeakNetworkOverview请求参数结构体
 *
 * @method string getStartTime() 获取开始时间（xxxx-xx-xx）如2019-08-14，默认为一周之前的日期。
 * @method void setStartTime(string $StartTime) 设置开始时间（xxxx-xx-xx）如2019-08-14，默认为一周之前的日期。
 * @method string getEndTime() 获取结束时间（xxxx-xx-xx）如2019-08-14，默认为昨天。
 * @method void setEndTime(string $EndTime) 设置结束时间（xxxx-xx-xx）如2019-08-14，默认为昨天。
 * @method array getFilters() 获取过滤条件。
region    String      是否必填：否     （过滤条件）按照region过滤,不支持模糊匹配。
 * @method void setFilters(array $Filters) 设置过滤条件。
region    String      是否必填：否     （过滤条件）按照region过滤,不支持模糊匹配。
 */
class DescribePeakNetworkOverviewRequest extends AbstractModel
{
    /**
     * @var string 开始时间（xxxx-xx-xx）如2019-08-14，默认为一周之前的日期。
     */
    public $StartTime;

    /**
     * @var string 结束时间（xxxx-xx-xx）如2019-08-14，默认为昨天。
     */
    public $EndTime;

    /**
     * @var array 过滤条件。
region    String      是否必填：否     （过滤条件）按照region过滤,不支持模糊匹配。
     */
    public $Filters;

    /**
     * @param string $StartTime 开始时间（xxxx-xx-xx）如2019-08-14，默认为一周之前的日期。
     * @param string $EndTime 结束时间（xxxx-xx-xx）如2019-08-14，默认为昨天。
     * @param array $Filters 过滤条件。
region    String      是否必填：否     （过滤条件）按照region过滤,不支持模糊匹配。
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
        if (array_key_exists("StartTime",$param) and $param["StartTime"] !== null) {
            $this->StartTime = $param["StartTime"];
        }

        if (array_key_exists("EndTime",$param) and $param["EndTime"] !== null) {
            $this->EndTime = $param["EndTime"];
        }

        if (array_key_exists("Filters",$param) and $param["Filters"] !== null) {
            $this->Filters = [];
            foreach ($param["Filters"] as $key => $value){
                $obj = new Filter();
                $obj->deserialize($value);
                array_push($this->Filters, $obj);
            }
        }
    }
}
