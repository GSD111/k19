<?php


namespace app\home\controller;


use app\BaseController;
use app\home\enum\StatusCode;
use app\home\model\CaptchaLogs;
use app\home\model\User;
use Overtrue\EasySms\EasySms;
use think\facade\Cache;
use think\facade\Request;
use think\facade\View;

class Homelogin extends BaseController
{
    public function Login()
    {
//        halt(Cache::get('mobile_captcha'));
        return View::fetch('home/login');
    }

    /*
     * 登录
     */
    public function SingIn()
    {

        $phone = Request::param('PhoneNumber');
        $captcha = Request::param('captcha');
//        halt($captcha,Cache::get('mobile_captcha'));
//        if (Cache::get('mobile_captcha') != $captcha) {
//            return "<script>alert('验证码有误');window.history.go(-1);</script>";
//        }
        if (!empty($phone)) {
            $result = User::where('PhoneNumber', $phone)->find();
//            halt($result->toArray());
            if (empty($result)) {
                $info = User::create([
                    'PhoneNumber' => $phone,
                    'CreateTime'=>time()
                ]);

//                halt($info->id);
                Cache::set('users', ['id' => $info->id, 'phone' => $info->PhoneNumber]);
                redirect('/home/index')->send();
            }
            if ($result['UserStatus'] != StatusCode::USER_STATUS) {
                return "<script>alert('您的账号存在异常无法登录，请联系管理进行处理');window.history.go(-1);</script>";
            }
            $result->PhoneNumber = $phone;
            $result->LastLoginTime = time();
            $result->save();
//            halt($result->ID);
            Cache::set('users', ['id' => $result->ID, 'phone' => $result->PhoneNumber,
                'doctor' => $result->IsDoctor,'persion'=>$result->IsPersion]);
            redirect('/home/index')->send();
        } else {
            return "<script>alert('手机号有误');window.history.go(-1);</script>";
        }
    }


    /*
     * 发送验证码
     */
    public function SendCaptcha()
    {

        $phone = $_GET['phone'];
//            halt($phone);
        $code = random_int(100000, 999999);
        Cache::set('mobile_captcha', $code, 600);
        $info = CaptchaLogs::where('PhoneNumber', $phone)->order('CreateTime desc')->find();
//         halt(strtotime($info['CreateTime']),time());
        $last = strtotime($info['CreateTime']);
        $now = time();
        if ($now - $last < 60) {
            return $data = ['status' => 500, 'msg' => '一分钟之内只能发送一次'];
        }
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
            $result = CaptchaLogs::create([
                'PhoneNumber' => $phone,
                'Captcha' => $code,
                'CreateTime' => time()
            ]);

            if ($result) {
                return $data = ['status' => 200, 'msg' => '发送成功'];
            }

        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
//            $msg = $exception->getException('aliyun')->getMessage();
//            halt($msg);
            return $data = ['status' => 500, 'msg' => '发送失败'];
        }

    }
}