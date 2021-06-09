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
//        $config = [
//            // HTTP 请求的超时时间（秒）
//            'timeout' => 5.0,
//
//            // 默认发送配置
//            'default' => [
//                // 网关调用策略，默认：顺序调用
//                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,
//
//                // 默认可用的发送网关
//                'gateways' => [
//                    'aliyun',
//                ],
//            ],
//            // 可用的网关配置
//            'gateways' => [
//                'errorlog' => [
//                    'file' => '/tmp/easy-sms.log',
//                ],
//                'aliyun' => [
//                    'access_key_id' => '',
//                    'access_key_secret' => '',
//                    'sign_name' => '',
//                ],
//                //...
//            ],
//        ];

        $phone = $_GET['phone'];
//            halt($phone);
        $code = random_int(100000, 999999);
        $lock = Cache::set('mobile_captcha_lock' , $phone, 60);
        if (Cache::has($lock)) {

            return '验证码未超过一分钟,不能发送';
        }
//        $countkey = 'mobile_captcha_count_'.$phone;
//        if(Cache::has($countkey)){
//            $count = Cache::inc('mobile_captcha_count_'.$phone);
//            if($count > 10){
//
//                return "验证码当天发送不能超过10次";
//            }
//        }else{
//            Cache::set($countkey,1);
//        }
//        Cache::set('mobile_captcha', $code, 600);




//
//        $easySms = new EasySms(config('sms'));
////        halt($easySms);
//        try {
//            $easySms->send(13251386670, [
//                'content' => '您的验证码为' . $code.'请勿泄露于他人！',
//                'template' => 'SMS_217495311',
//                'data' => [
//                    'code' => $code
//                ]
//            ]);
//        }catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception){
//            $msg = $exception->getException('aliyun')->getMessage();
//            halt($msg);
//        }

//        halt($bool);
//        if ($bool) {
//            return 111;
//        } else {
//            return 000;
//        }
    }
}