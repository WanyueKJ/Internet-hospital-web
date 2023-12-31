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
namespace TencentCloud\Cmq\V20190304\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 批量queue属性信息
 *
 * @method string getQueueId() 获取QueueId
 * @method void setQueueId(string $QueueId) 设置QueueId
 * @method string getQueueName() 获取QueueName
 * @method void setQueueName(string $QueueName) 设置QueueName
 * @method integer getQps() 获取Qps
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setQps(integer $Qps) 设置Qps
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getBps() 获取Bps
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setBps(integer $Bps) 设置Bps
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getMaxDelaySeconds() 获取MaxDelaySeconds
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setMaxDelaySeconds(integer $MaxDelaySeconds) 设置MaxDelaySeconds
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getMaxMsgHeapNum() 获取MaxMsgHeapNum
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setMaxMsgHeapNum(integer $MaxMsgHeapNum) 设置MaxMsgHeapNum
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getPollingWaitSeconds() 获取PollingWaitSeconds
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setPollingWaitSeconds(integer $PollingWaitSeconds) 设置PollingWaitSeconds
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getMsgRetentionSeconds() 获取MsgRetentionSeconds
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setMsgRetentionSeconds(integer $MsgRetentionSeconds) 设置MsgRetentionSeconds
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getVisibilityTimeout() 获取VisibilityTimeout
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setVisibilityTimeout(integer $VisibilityTimeout) 设置VisibilityTimeout
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getMaxMsgSize() 获取MaxMsgSize
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setMaxMsgSize(integer $MaxMsgSize) 设置MaxMsgSize
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getRewindSeconds() 获取RewindSeconds
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setRewindSeconds(integer $RewindSeconds) 设置RewindSeconds
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getCreateTime() 获取CreateTime
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setCreateTime(integer $CreateTime) 设置CreateTime
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getLastModifyTime() 获取LastModifyTime
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setLastModifyTime(integer $LastModifyTime) 设置LastModifyTime
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getActiveMsgNum() 获取ActiveMsgNum
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setActiveMsgNum(integer $ActiveMsgNum) 设置ActiveMsgNum
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getInactiveMsgNum() 获取InactiveMsgNum
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setInactiveMsgNum(integer $InactiveMsgNum) 设置InactiveMsgNum
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getDelayMsgNum() 获取DelayMsgNum
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setDelayMsgNum(integer $DelayMsgNum) 设置DelayMsgNum
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getRewindMsgNum() 获取RewindMsgNum
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setRewindMsgNum(integer $RewindMsgNum) 设置RewindMsgNum
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getMinMsgTime() 获取MinMsgTime
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setMinMsgTime(integer $MinMsgTime) 设置MinMsgTime
注意：此字段可能返回 null，表示取不到有效值。
 * @method boolean getTransaction() 获取Transaction
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setTransaction(boolean $Transaction) 设置Transaction
注意：此字段可能返回 null，表示取不到有效值。
 * @method array getDeadLetterSource() 获取DeadLetterSource
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setDeadLetterSource(array $DeadLetterSource) 设置DeadLetterSource
注意：此字段可能返回 null，表示取不到有效值。
 * @method DeadLetterPolicy getDeadLetterPolicy() 获取DeadLetterPolicy
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setDeadLetterPolicy(DeadLetterPolicy $DeadLetterPolicy) 设置DeadLetterPolicy
注意：此字段可能返回 null，表示取不到有效值。
 * @method TransactionPolicy getTransactionPolicy() 获取TransactionPolicy
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setTransactionPolicy(TransactionPolicy $TransactionPolicy) 设置TransactionPolicy
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getCreateUin() 获取创建者uin
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setCreateUin(integer $CreateUin) 设置创建者uin
注意：此字段可能返回 null，表示取不到有效值。
 * @method array getTags() 获取标签
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setTags(array $Tags) 设置标签
注意：此字段可能返回 null，表示取不到有效值。
 * @method boolean getTrace() 获取消息轨迹表示，true表示开启，false表示不开启
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setTrace(boolean $Trace) 设置消息轨迹表示，true表示开启，false表示不开启
注意：此字段可能返回 null，表示取不到有效值。
 */
class QueueSet extends AbstractModel
{
    /**
     * @var string QueueId
     */
    public $QueueId;

    /**
     * @var string QueueName
     */
    public $QueueName;

    /**
     * @var integer Qps
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Qps;

    /**
     * @var integer Bps
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Bps;

    /**
     * @var integer MaxDelaySeconds
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $MaxDelaySeconds;

    /**
     * @var integer MaxMsgHeapNum
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $MaxMsgHeapNum;

    /**
     * @var integer PollingWaitSeconds
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $PollingWaitSeconds;

    /**
     * @var integer MsgRetentionSeconds
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $MsgRetentionSeconds;

    /**
     * @var integer VisibilityTimeout
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $VisibilityTimeout;

    /**
     * @var integer MaxMsgSize
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $MaxMsgSize;

    /**
     * @var integer RewindSeconds
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $RewindSeconds;

    /**
     * @var integer CreateTime
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $CreateTime;

    /**
     * @var integer LastModifyTime
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $LastModifyTime;

    /**
     * @var integer ActiveMsgNum
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $ActiveMsgNum;

    /**
     * @var integer InactiveMsgNum
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $InactiveMsgNum;

    /**
     * @var integer DelayMsgNum
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $DelayMsgNum;

    /**
     * @var integer RewindMsgNum
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $RewindMsgNum;

    /**
     * @var integer MinMsgTime
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $MinMsgTime;

    /**
     * @var boolean Transaction
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Transaction;

    /**
     * @var array DeadLetterSource
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $DeadLetterSource;

    /**
     * @var DeadLetterPolicy DeadLetterPolicy
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $DeadLetterPolicy;

    /**
     * @var TransactionPolicy TransactionPolicy
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $TransactionPolicy;

    /**
     * @var integer 创建者uin
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $CreateUin;

    /**
     * @var array 标签
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Tags;

    /**
     * @var boolean 消息轨迹表示，true表示开启，false表示不开启
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Trace;

    /**
     * @param string $QueueId QueueId
     * @param string $QueueName QueueName
     * @param integer $Qps Qps
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $Bps Bps
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $MaxDelaySeconds MaxDelaySeconds
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $MaxMsgHeapNum MaxMsgHeapNum
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $PollingWaitSeconds PollingWaitSeconds
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $MsgRetentionSeconds MsgRetentionSeconds
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $VisibilityTimeout VisibilityTimeout
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $MaxMsgSize MaxMsgSize
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $RewindSeconds RewindSeconds
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $CreateTime CreateTime
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $LastModifyTime LastModifyTime
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $ActiveMsgNum ActiveMsgNum
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $InactiveMsgNum InactiveMsgNum
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $DelayMsgNum DelayMsgNum
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $RewindMsgNum RewindMsgNum
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $MinMsgTime MinMsgTime
注意：此字段可能返回 null，表示取不到有效值。
     * @param boolean $Transaction Transaction
注意：此字段可能返回 null，表示取不到有效值。
     * @param array $DeadLetterSource DeadLetterSource
注意：此字段可能返回 null，表示取不到有效值。
     * @param DeadLetterPolicy $DeadLetterPolicy DeadLetterPolicy
注意：此字段可能返回 null，表示取不到有效值。
     * @param TransactionPolicy $TransactionPolicy TransactionPolicy
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $CreateUin 创建者uin
注意：此字段可能返回 null，表示取不到有效值。
     * @param array $Tags 标签
注意：此字段可能返回 null，表示取不到有效值。
     * @param boolean $Trace 消息轨迹表示，true表示开启，false表示不开启
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
        if (array_key_exists("QueueId",$param) and $param["QueueId"] !== null) {
            $this->QueueId = $param["QueueId"];
        }

        if (array_key_exists("QueueName",$param) and $param["QueueName"] !== null) {
            $this->QueueName = $param["QueueName"];
        }

        if (array_key_exists("Qps",$param) and $param["Qps"] !== null) {
            $this->Qps = $param["Qps"];
        }

        if (array_key_exists("Bps",$param) and $param["Bps"] !== null) {
            $this->Bps = $param["Bps"];
        }

        if (array_key_exists("MaxDelaySeconds",$param) and $param["MaxDelaySeconds"] !== null) {
            $this->MaxDelaySeconds = $param["MaxDelaySeconds"];
        }

        if (array_key_exists("MaxMsgHeapNum",$param) and $param["MaxMsgHeapNum"] !== null) {
            $this->MaxMsgHeapNum = $param["MaxMsgHeapNum"];
        }

        if (array_key_exists("PollingWaitSeconds",$param) and $param["PollingWaitSeconds"] !== null) {
            $this->PollingWaitSeconds = $param["PollingWaitSeconds"];
        }

        if (array_key_exists("MsgRetentionSeconds",$param) and $param["MsgRetentionSeconds"] !== null) {
            $this->MsgRetentionSeconds = $param["MsgRetentionSeconds"];
        }

        if (array_key_exists("VisibilityTimeout",$param) and $param["VisibilityTimeout"] !== null) {
            $this->VisibilityTimeout = $param["VisibilityTimeout"];
        }

        if (array_key_exists("MaxMsgSize",$param) and $param["MaxMsgSize"] !== null) {
            $this->MaxMsgSize = $param["MaxMsgSize"];
        }

        if (array_key_exists("RewindSeconds",$param) and $param["RewindSeconds"] !== null) {
            $this->RewindSeconds = $param["RewindSeconds"];
        }

        if (array_key_exists("CreateTime",$param) and $param["CreateTime"] !== null) {
            $this->CreateTime = $param["CreateTime"];
        }

        if (array_key_exists("LastModifyTime",$param) and $param["LastModifyTime"] !== null) {
            $this->LastModifyTime = $param["LastModifyTime"];
        }

        if (array_key_exists("ActiveMsgNum",$param) and $param["ActiveMsgNum"] !== null) {
            $this->ActiveMsgNum = $param["ActiveMsgNum"];
        }

        if (array_key_exists("InactiveMsgNum",$param) and $param["InactiveMsgNum"] !== null) {
            $this->InactiveMsgNum = $param["InactiveMsgNum"];
        }

        if (array_key_exists("DelayMsgNum",$param) and $param["DelayMsgNum"] !== null) {
            $this->DelayMsgNum = $param["DelayMsgNum"];
        }

        if (array_key_exists("RewindMsgNum",$param) and $param["RewindMsgNum"] !== null) {
            $this->RewindMsgNum = $param["RewindMsgNum"];
        }

        if (array_key_exists("MinMsgTime",$param) and $param["MinMsgTime"] !== null) {
            $this->MinMsgTime = $param["MinMsgTime"];
        }

        if (array_key_exists("Transaction",$param) and $param["Transaction"] !== null) {
            $this->Transaction = $param["Transaction"];
        }

        if (array_key_exists("DeadLetterSource",$param) and $param["DeadLetterSource"] !== null) {
            $this->DeadLetterSource = [];
            foreach ($param["DeadLetterSource"] as $key => $value){
                $obj = new DeadLetterSource();
                $obj->deserialize($value);
                array_push($this->DeadLetterSource, $obj);
            }
        }

        if (array_key_exists("DeadLetterPolicy",$param) and $param["DeadLetterPolicy"] !== null) {
            $this->DeadLetterPolicy = new DeadLetterPolicy();
            $this->DeadLetterPolicy->deserialize($param["DeadLetterPolicy"]);
        }

        if (array_key_exists("TransactionPolicy",$param) and $param["TransactionPolicy"] !== null) {
            $this->TransactionPolicy = new TransactionPolicy();
            $this->TransactionPolicy->deserialize($param["TransactionPolicy"]);
        }

        if (array_key_exists("CreateUin",$param) and $param["CreateUin"] !== null) {
            $this->CreateUin = $param["CreateUin"];
        }

        if (array_key_exists("Tags",$param) and $param["Tags"] !== null) {
            $this->Tags = [];
            foreach ($param["Tags"] as $key => $value){
                $obj = new Tag();
                $obj->deserialize($value);
                array_push($this->Tags, $obj);
            }
        }

        if (array_key_exists("Trace",$param) and $param["Trace"] !== null) {
            $this->Trace = $param["Trace"];
        }
    }
}
