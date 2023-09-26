<?php
namespace App\Domain;

use App\Domain\Wxpay as Domain_Wxpay;
use App\Domain\Alipay as Domain_Alipay;

/* 三方支付 */
class Pay {

    /* 支付方式 */
    public static function getPayType($k=''){
        $type=[
            '1'=>'支付宝',
            '2'=>'微信APP',
            '3'=>'微信小程序',
            '4'=>'微信外H5',
            '5'=>'微信内H5',
            '6'=>'苹果支付',
            '7'=>'余额支付',
        ];
        if($k==''){
            return $type;
        }
        return  $type[$k] ?? '' ;
    }

    public static function pay($orderno,$money,$payid,$title,$backmodel,$openid=''){

        $ali=[
            'orderinfo'=>'',
        ];
        $wx=[
            'appid'=>'',
            'noncestr'=>'',
            'package'=>'',
            'partnerid'=>'',
            'prepayid'=>'',
            'timestamp'=>'',
        ];

        $ios=[
            'notifyurl'=>'',
        ];

        $small='';
        $h5='';
        $mp='';
        $web='';

    
        if($payid ==2){
            /* 微信app */
            $url=\App\get_upload_path("/appapi/{$backmodel}/notify_wx");
            $wx=Wxpay::wxPay($orderno,$money,$url,$title);
        }

        if($payid == 3) {
            //UNIAPP端小程序支付
            $url=\App\get_upload_path("/appapi/{$backmodel}/notify_small");
            $small=Wxpay::smallPay($orderno,$money,$url,$title, $openid);

        }
        if($payid == 4) {
            //UNIAPP端 H5支付
            $url=\App\get_upload_path("/appapi/{$backmodel}/notify_hfive");
            $h5=Wxpay::hfivePay($orderno,$money,$url,$title, $openid);
        }

        if($payid == 5){
            /* 微信内支付 */
            $url=\App\get_upload_path("/appapi/{$backmodel}/notify_mp");
            $mp=Wxpay::mpPay($orderno,$money,$url,$title, $openid);
        }

        if($payid==6){
            /* WEB支付 */
            /* 微信内支付 */
            $url=\App\get_upload_path("/appapi/{$backmodel}/notify_web");
            $web=Wxpay::webPay($orderno,$money,$url,$title, $openid);
        }

        if($payid==7){
            /* 苹果支付 */
            $ios=[
                'notifyurl'=>\App\get_upload_path("/appapi/{$backmodel}/notify_ios"),
            ];
        }

        $info['orderid']=$orderno;
        $info['money']=(string)$money;
        $info['ali']=$ali;
        $info['wx']=$wx;
        $info['ios']=$ios;
        $info['small']=$small;
        $info['h5']=$h5;
        $info['mp']=$mp;
        $info['web']=$web;

        return $info;
    }
}
