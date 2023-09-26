<?php

namespace App\Domain;

use App\ApiException;
use App\Domain\User as Domain_User;
use App\Domain\Usertoken as Usertoken;
use App\Model\User as Model_User;

class User
{


    /**
     * 退出登录
     * @param $uid
     * @return void
     */
    public function logOut($uid)
    {
        $Model_Usertoken = new Usertoken();
        $Model_Usertoken->up(['user_id'=>$uid], ['token'=>null]);
        \App\setcaches("token_" . $uid,'',0);
    }

    /**
     * 账号注销(删除账号,三方,toke信息)
     * @param $uid
     * @return void
     */
    public function setWriteOff($uid)
    {
        $rs = array('code' => 0, 'msg' => \PhalApi\T('操作成功'), 'info' => array());

        $Model_User = new Model_User();
        $Usertoken = new Usertoken();

        \PhalApi\DI()->notorm->beginTransaction('db_master');

        try {
            $Model_User->del(['id = ?' => $uid]);
            $Usertoken->del(['user_id = ?' => $uid]);
        } catch (\Exception $e) {
            \PhalApi\DI()->notorm->rollback('db_master');
            $rs['code'] = 995;
            $rs['msg'] = $e->getMessage();
            return $rs;
        }
        \PhalApi\DI()->notorm->commit('db_master');

        return $rs;

    }

    /**
     * 更新手机号
     * @return void
     */
    public function upMobile($uid, $new_mobile)
    {
        $rs = array('code' => 0, 'msg' => \PhalApi\T('操作成功'), 'info' => array());

        $model = new Model_User();
        $field = 'id,user_nickname,avatar,avatar_thumb,sex,signature,balance,birthday,mobile';
        $where = ['mobile' => $new_mobile];
        $info = $model->getInfo($where, $field);
        if ($info) {
            throw new ApiException('该手机号已被绑定过,请绑定一个新手机号');
        }

        $domain = new Domain_User();
        $domain->up(['id = ?' => $uid], ['mobile' => $new_mobile]);
        return $rs;
    }





    /* 用户基本信息 */
    public function getBaseInfo($uid)
    {

        $model = new Model_User();
        $field = 'id,user_nickname,avatar,avatar_thumb,sex,signature,balance,birthday,mobile';
        $where = ['id' => $uid];
        $info = $model->getInfo($where, $field);

        if ($info) {
            //$birthday=$info['birthday'];
            $info = \App\handleUser($info);
            //$info['birthday']=date('Y-m-d',$birthday);

            unset($info['birthday']);
        }
        $info['welcome'] = $this->getWelcomeText();

        return $info;
    }

    public function getWelcomeText()
    {
        $configpri = \App\getConfigPri();
        $timeList = $configpri['welcome_time'] ?? [];
        $welcomeDefault = $configpri['welcome_default'] ?? '';
        foreach ($timeList as $value) {
            if (time() >= strtotime(date("Y-m-d {$value['star']}")) && time() <= strtotime(date("Y-m-d {$value['end']}"))) {
                return $value['text'];
            }
        }

        return $welcomeDefault;
    }

    /* 更新基本信息 */
    public function upUserInfo($uid, $fields = [])
    {

        $rs = array('code' => 0, 'msg' => \PhalApi\T('操作成功'), 'info' => array());

        $model = new Model_User();
        $data = [];
        $info = [];
        /* 头像 */
        if (isset($fields['avatar']) && $fields['avatar'] != '') {
            $avatar_q = $fields['avatar'];

            $avatar = $avatar_q;
            $avatar_thumb = $avatar_q;

            $data['avatar'] = $avatar;
            $data['avatar_thumb'] = $avatar_thumb;

            $info['avatar'] = \App\get_upload_path($avatar);
            $info['avatar_thumb'] = \App\get_upload_path($avatar_thumb);

        }


        /* 昵称 */
        if (isset($fields['user_nickname']) && $fields['user_nickname'] != '') {
            $name = $fields['user_nickname'];
            $count = mb_strlen($name);
            if ($count > 10) {
                $rs['code'] = 1002;
                $rs['msg'] = \PhalApi\T('昵称最多10个字');
                return $rs;
            }

            /*$isexist = $this->checkNickname($uid,$name);
            if($isexist){
                $rs['code'] = 1003;
                $rs['msg'] = \PhalApi\T('昵称已存在');
                return $rs;
            }*/

            $data['user_nickname'] = $name;
            $info['user_nickname'] = $name;
        }

        /* 生日 年龄 */
        if (isset($fields['birthday']) && $fields['birthday'] != '') {
            $birthday = strtotime($fields['birthday']);
            $age = \App\getAge($birthday);

            $data['birthday'] = $birthday;

            $info['birthday'] = $birthday;
            $info['age'] = $age;
        }

        /* 性别 */
        if (isset($fields['sex']) && $fields['sex'] != '') {
            $sex = $fields['sex'];
            $data['sex'] = $sex;
            $info['sex'] = $sex;
        }
        /* 签名 */
        if (isset($fields['signature']) && $fields['signature'] != '') {
            $signature = $fields['signature'];

            $data['signature'] = $signature;
            $info['signature'] = $signature;
        }


        if (!$data) {
            $rs['code'] = 1003;
            $rs['msg'] = \PhalApi\T('信息错误');
            return $rs;
        }
        $where = ['id' => $uid];
        $result = $model->up($where, $data);

        \App\delcache("userinfo_" . $uid);

        $rs['info'][0] = $info;
        return $rs;
    }

    public function checkNickname($uid, $name)
    {

        $model = new Model_User();
        $where = [
            'id!=?' => $uid,
            'user_nickname' => $name,
        ];

        $isexist = $model->getInfo($where, 'id');

        if ($isexist) {
            return 1;
        }

        return 0;
    }

    /* 根据条件获取用户ID */
    public function getAll($where, $field = '*')
    {

        $model = new Model_User();
        $list = $model->getAll($where, $field);

        return $list;
    }

    public function getInfo($where, $field = '*')
    {

        $model = new Model_User();
        $info = $model->getInfo($where, $field);

        return $info;
    }

    public function up($where, $data)
    {

        $model = new Model_User();
        $info = $model->up($where, $data);

        return $info;
    }

    public function upField($where, $field, $nums)
    {

        $model = new Model_User();
        $info = $model->upField($where, $field, $nums);

        return $info;
    }

    public function del($uid)
    {

        $model = new Model_User();
        $model->del(['id' => $uid]);

        \App\delcache("userinfo_" . $uid);
        \App\delcache("token_" . $uid);

        return 1;
    }

    public function upPass($uid, $oldpass, $newpass)
    {

        $rs = ['code' => 0, 'msg' => \PhalApi\T('修改成功'), 'info' => []];

        if ($oldpass == '') {
            $rs['code'] = 1001;
            $rs['msg'] = \PhalApi\T('请输入旧密码');
            return $rs;
        }

        if ($newpass == '') {
            $rs['code'] = 1002;
            $rs['msg'] = \PhalApi\T('请输入新密码');
            return $rs;
        }

        if (!\App\checkPass($newpass)) {
            $rs['code'] = 1003;
            $rs['msg'] = \PhalApi\T('密码为6-20位字母数字组合');
            return $rs;
        }

        if ($oldpass == $newpass) {
            $rs['code'] = 1004;
            $rs['msg'] = \PhalApi\T('新旧密码不能一样');
            return $rs;
        }

        $model = new Model_User();
        $info = $model->getInfo(['id' => $uid], 'user_pass');
        if (!$info) {
            $rs['code'] = 700;
            $rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        if (\App\setPass($oldpass) != $info['user_pass']) {
            $rs['code'] = 1005;
            $rs['msg'] = \PhalApi\T('旧密码错误，请更正');
            return $rs;
        }

        $up = [
            'user_pass' => \App\setPass($newpass)
        ];

        $model->up(['id' => $uid], $up);

        return $rs;
    }

    /**
     * 获取国家区号
     * @return array
     */
    public function getCountryCode()
    {
        $model = new Model_User();

        $cacheKey = 'country_code';
        $list = \App\getcaches($cacheKey);
        if ($list) {
            return $list;
        }
        $list = [];
        $letterList = $model->getCountryCode([], 'initial', 'initial');
        ksort($letterList);
        foreach ($letterList as $key => &$value) {
            $children = $model->getCountryCode(['initial = ?' => $value['initial']], '', 'id,code,country');
            $list[strtoupper($value['initial'])] = $children;
        }
        \App\setcaches($cacheKey, $list);
        return $list;
    }
}
