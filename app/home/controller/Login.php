<?php


namespace app\home\controller;


use app\BaseController;
use Overtrue\EasySms\EasySms;
use think\facade\Cache;
use think\facade\Request;

class Login extends BaseController
{


    public function Sms()
    {
        $config = [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,

            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

                // 默认可用的发送网关
                'gateways' => [
                    'aliyun',
                ],
            ],
            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => '/tmp/easy-sms.log',
                ],
                'aliyun' => [
                    'access_key_id' => 'LTAI5t9U6Rp7vqDWLv4o4ADb',
                    'access_key_secret' => 'fMeR6sQWBZhghYhHLUEvs7u6nIoJzR',
                    'sign_name' => '',
                ],
                //...
            ],
        ];

        $phone = Request::param('phone');

        $code = random_int(10000, 99999);

        $lock = Cache::set('mobile_captcha_lock_' . $phone, 1, 60);
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

        Cache::set('mobile_captcha_' . $phone, $code, 600);

        $easySms = new EasySms($config);
//        halt($easySms);
        $bool = $easySms->send($phone, [
            'content' => '您的验证码为' . $code,
            'template' => 'SMS_217495311',
            'data' => [
                'code' => $code
            ]
        ]);
        halt($bool);
        if ($bool) {
            return 111;
        } else {
            return 000;
        }
    }
}