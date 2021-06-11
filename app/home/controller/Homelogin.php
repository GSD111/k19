<?php


namespace app\home\controller;


use app\BaseController;
use app\home\enum\StatusCode;
use app\home\model\User;
use Overtrue\EasySms\EasySms;
use think\facade\Cache;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;

class Homelogin extends BaseController
{
    public function Login()
    {
//        halt(Cache::pull('users'));
        return View::fetch('home/login');
    }

    public function SingIn()
    {
        $phone = Request::param('PhoneNumber');
        $captcha = Request::param('captcha');
        if (!empty($phone)) {
            $result = User::where('PhoneNumber', $phone)->find();
//            dump($result);
//            if (Cache::get('mobile_captcha') != $captcha) {
//                return "<script>alert('验证码有误');</script>";
//            }
            if (empty($result)) {
                User::create([
                    'PhoneNumber' => $phone,
                ]);
                redirect('/home/index')->send();
            }
            if ($result->UserStatus != StatusCode::USER_STATUS) {
                return "<script>alert('您的账号存在异常无法登录，请联系管理进行处理');</script>";
            }
            $result->PhoneNumber = $phone;
            $result->save();
            Cache::set('users',['id'=>$result->ID,'phone'=>$result->PhoneNumber,'type'=>$result->UserType],60);
//            Session::set('users',['id'=>$result->ID,'phone'=>$result->PhoneNumber,'type'=>$result->UserType]);
//            dump($result->ID,$result->PhoneNumber,$result->UserType);
//            Cache::get('users');
            redirect('/home/index')->send();
        }
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
            return $data = ['status' => 200, 'msg' => '发送成功'];
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
//            $msg = $exception->getException('aliyun')->getMessage();
//            halt($msg);
            return $data = ['status' => 500, 'msg' => '发送失败'];
        }

    }
}