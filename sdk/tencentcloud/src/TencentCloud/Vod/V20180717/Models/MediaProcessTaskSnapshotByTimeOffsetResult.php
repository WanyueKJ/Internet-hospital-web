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
namespace TencentCloud\Vod\V20180717\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 对视频按指定时间点截图任务结果类型
 *
 * @method string getStatus() 获取任务状态，有 PROCESSING，SUCCESS 和 FAIL 三种。
 * @method void setStatus(string $Status) 设置任务状态，有 PROCESSING，SUCCESS 和 FAIL 三种。
 * @method integer getErrCode() 获取错误码，0 表示成功，其他值表示失败：
<li>40000：输入参数不合法，请检查输入参数；</li>
<li>60000：源文件错误（如视频数据损坏），请确认源文件是否正常；</li>
<li>70000：内部服务错误，建议重试。</li>
 * @method void setErrCode(integer $ErrCode) 设置错误码，0 表示成功，其他值表示失败：
<li>40000：输入参数不合法，请检查输入参数；</li>
<li>60000：源文件错误（如视频数据损坏），请确认源文件是否正常；</li>
<li>70000：内部服务错误，建议重试。</li>
 * @method string getMessage() 获取错误信息。
 * @method void setMessage(string $Message) 设置错误信息。
 * @method SnapshotByTimeOffsetTaskInput getInput() 获取对视频按指定时间点截图任务输入。
 * @method void setInput(SnapshotByTimeOffsetTaskInput $Input) 设置对视频按指定时间点截图任务输入。
 * @method MediaSnapshotByTimeOffsetItem getOutput() 获取对视频按指定时间点截图任务输出。
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setOutput(MediaSnapshotByTimeOffsetItem $Output) 设置对视频按指定时间点截图任务输出。
注意：此字段可能返回 null，表示取不到有效值。
 */
class MediaProcessTaskSnapshotByTimeOffsetResult extends AbstractModel
{
    /**
     * @var string 任务状态，有 PROCESSING，SUCCESS 和 FAIL 三种。
     */
    public $Status;

    /**
     * @var integer 错误码，0 表示成功，其他值表示失败：
<li>40000：输入参数不合法，请检查输入参数；</li>
<li>60000：源文件错误（如视频数据损坏），请确认源文件是否正常；</li>
<li>70000：内部服务错误，建议重试。</li>
     */
    public $ErrCode;

    /**
     * @var string 错误信息。
     */
    public $Message;

    /**
     * @var SnapshotByTimeOffsetTaskInput 对视频按指定时间点截图任务输入。
     */
    public $Input;

    /**
     * @var MediaSnapshotByTimeOffsetItem 对视频按指定时间点截图任务输出。
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Output;

    /**
     * @param string $Status 任务状态，有 PROCESSING，SUCCESS 和 FAIL 三种。
     * @param integer $ErrCode 错误码，0 表示成功，其他值表示失败：
<li>40000：输入参数不合法，请检查输入参数；</li>
<li>60000：源文件错误（如视频数据损坏），请确认源文件是否正常；</li>
<li>70000：内部服务错误，建议重试。</li>
     * @param string $Message 错误信息。
     * @param SnapshotByTimeOffsetTaskInput $Input 对视频按指定时间点截图任务输入。
     * @param MediaSnapshotByTimeOffsetItem $Output 对视频按指定时间点截图任务输出。
注意：此字段可能返回 null，表示取不到有效值。
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
        if (array_key_exists("Status",$param) and $param["Status"] !== null) {
            $this->Status = $param["Status"];
        }

        if (array_key_exists("ErrCode",$param) and $param["ErrCode"] !== null) {
            $this->ErrCode = $param["ErrCode"];
        }

        if (array_key_exists("Message",$param) and $param["Message"] !== null) {
            $this->Message = $param["Message"];
        }

        if (array_key_exists("Input",$param) and $param["Input"] !== null) {
            $this->Input = new SnapshotByTimeOffsetTaskInput();
            $this->Input->deserialize($param["Input"]);
        }

        if (array_key_exists("Output",$param) and $param["Output"] !== null) {
            $this->Output = new MediaSnapshotByTimeOffsetItem();
            $this->Output->deserialize($param["Output"]);
        }
    }
}
