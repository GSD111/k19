<?php


namespace app\home\controller;


use app\BaseController;
use Overtrue\EasySms\EasySms;
use think\facade\Cache;

//use think\facade\Request;

class Login extends BaseController
{


    public function Sms()
    {
        $phone = $_GET['phone'];
//            halt($phone);
        $code = random_int(100000, 999999);
        $lock = Cache::set('mobile_captcha_lock', $phone, 60);
//        halt($lock);
        if (!$lock) {
            return '验证码未超过一分钟,不能发送';
        }
//        $countkey = 'mobile_captcha_count_' . $phone;
////        halt($countkey);
//        if (Cache::has($countkey)) {
//            $count = Cache::inc('mobile_captcha_count_' . $phone);
//            halt($count);
//            if ($count > 5) {
//
//                return "验证码超出发送限制请稍后在试";
//            }
//        } else {
//            Cache::set($countkey, 1, 7200);
//        }
        Cache::set('mobile_captcha', $code, 600);


//
        $easySms = new EasySms(config('sms'));
//        halt($easySms);
        try {
            $easySms->send($phone, [
                'content' => '您的验证码为' . $code . '请勿泄露于他人！',
                'template' => 'SMS_217495311',
                'data' => [
                    'code' => $code
                ]
            ]);
            return ['status' => 200, 'msg' => '发送成功'];
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
//            $msg = $exception->getException('aliyun')->getMessage();
//            halt($msg);
            return ['status' => 500, 'msg' => '发送失败'];
        }

//        halt($bool);
//        if ($bool) {
//            return 111;
//        } else {
//            return 000;
//        }
    }
}