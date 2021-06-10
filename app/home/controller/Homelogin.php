<?php


namespace app\home\controller;


use app\BaseController;
use app\home\enum\StatusCode;
use app\home\model\User;
use Overtrue\EasySms\EasySms;
use think\facade\Cache;
use think\facade\Request;
use think\facade\View;

class Homelogin extends BaseController
{
    public function Login()
    {
        $phone = Request::param('PhoneNumber');
        $captcha = Request::param('captcha');
//        dump(Request::getInput('PhoneNumber','captcha'));
//        dump(Request::param('captcha'));
        $result = User::where('PhoneNumber',$phone)->find();
//        dump($result->UserStatus);
//        if(Cache::get('mobile_captcha') != $captcha){
//
//            echo "验证码有误";
//        }
        if($result->UserStatus != StatusCode::USER_STATUS){
            echo "您的账号存在异常无法登录，请联系管理进行处理";
        }

        return View::fetch('home/login');
    }

    public function SendCaptcha()
    {

        $phone = $_GET['phone'];
//            halt($phone);
        $code = random_int(100000, 999999);
        $lock = Cache::set('mobile_captcha_lock', $phone, 60);
//        halt($lock);
        if (!$lock) {
            return '验证码未超过一分钟,不能发送';
        }
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

    }
}