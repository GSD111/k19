<?php


namespace app\home\controller;


use app\BaseController;
use app\home\enum\StatusCode;
use app\home\model\TypeList;
use app\home\model\UserOrder;
use app\home\model\UserTestOrder;
use app\home\service\Notify;
use app\home\service\User as UserService;
use app\home\service\WxpayService;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Log;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;

class WxPay extends BaseController
{

    public function PayTest()
    {

        $info = TypeList::GetTypeInfo(Request::param('type_id'));
        $result = UserService::UserTestQuestion(Request::param('type_id'), StatusCode::USER_TEST_PAY)->toArray();
//        p($info);
//        halt($info);
        if (empty($result)) {
            return "<script>alert('该题库正在策划中，敬请期待!');window.history.back();</script>";
        }

        $user_test = Db::table('usertest')
            ->join('usertestorder', 'usertestorder.OrderNo = usertest.OrderNo')
            ->field('usertestorder.OrderNo order_no,usertestorder.is_use,usertest.*')
            ->where('usertest.UserId', Session::get('users')['id'])
            ->order('CreateTime desc')
            ->find();
//        halt($user_test);
//        halt(Session::get('users')['id'],Cache::get('user_id'));
        if ($info['Title'] == $user_test['TestType'] && $user_test['is_use'] == 1) {
            //            return 111;
            return redirect('/home/ylcs03?type_id=' . Request::param('type_id') . '&user_test_id=' . $user_test['ID']);
        }
//        Cache::set('pay_type',1,1800);
        $wxPay = new WxpayService();
////        $wxPay->getSign($arr);
////        $arr = $wxPay->setSign($arr);
////        print_r($arr);
//
        $pay_info = $wxPay->unifiedOrder($info['Title'], $info['SalePrice'], 1);

        $data = $wxPay->GetJsParams($pay_info['prepay_id']);
//        halt($data);
        View::assign('data', $data);
        View::assign('info', $info);
        return View::fetch('/home/pay_test');
    }

    public function receiveNotify()
    {

        $notify = new Notify();
//        $notify->notify();
        $result = $notify->notify();
        if ($result) {
            Log::record('我走到回调里了');
            $order_info = array(
////                    'openId' => $arr['appid'],
                'OrderNo' => $result['out_trade_no'],
                'Money' => $result['total_fee'] / 100,
//                "UserId" => Session::get('user_id')['id'],
                'UserId' => Cache::get('user_id'),
                'PayTime' => $result['time_end'],
                'CreateTime' => time(),
                'Status' => StatusCode::USER_ORDER_STATUS_PAID
            );
            Db::startTrans();
            try {
//                    Log::record(Session::get('users')['id']);
                if ($result['attach'] == 1) {
                    Log::record('我要处理测试的数据了');
                    UserTestOrder::create($order_info);//交易成功将支付信息写入表中
                } else {
                    Log::record('我要处理倾述的数据了');
                    UserOrder::where('ID', Cache::get('user_talk_id'))
                        ->where('Money', $result['total_fee'] / 100)
//                        ->allowField(['OrderNo', 'Money', 'PayTime', 'CreateTime', 'Status'])
                        ->save($order_info);
                }

//                    Db::table('usertestorder')->insert($order_info);   //交易成功将支付信息写入表中
                Db::commit();
                Cache::set('order_no', $result['out_trade_no'], 180);
//                   Cookie::set('order_no',$arr['out_trade_no'],600);
//                   Session::set('order_no',$arr['out_trade_no']) ;
//                    return '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';

            } catch (\Exception $e) {
                Db::rollback();
                throw $e;
//                    halt($e->getMessage());

            }
        }

//        $notify->talk_notify();

        //1获取通知数据(原始数据格式为XML)->转换成数组
        //2验证签名
        //3验证业务结果(return_code 和 result_code)
        //4验证订单号和支付金额(out_trade_no 和 total_fee)
        //5记录日志 修改订单状态(然后根据自己的业务进行处理下一步操作)
    }


    public function PayTalk()
    {
//         $user_talk = UserOrder::where('ID',Request::param('id'))->find();

        $user_talk = Db::table('user')
            ->join('userorder', 'userorder.DoctorID = user.ID')
            ->field('user.RealName,user.ID UID,userorder.*')
            ->where('userorder.ID', Request::param('id'))
            ->find();
//        p(Cache::get('user_talk_id'));
//        Cache::set('pay_type',2,1800);
//        Cache::get('pay_type');
        $wxPay = new WxpayService();
////        $wxPay->getSign($arr);
////        $arr = $wxPay->setSign($arr);
////        print_r($arr);
//
        $pay_info = $wxPay->unifiedOrder('倾诉' . $user_talk['Specialty'], $user_talk['Money'], 2);
//        halt($pay_info);
        $data = $wxPay->GetJsParams($pay_info['prepay_id']);


        View::assign('data', $data);
        View::assign('user_talk', $user_talk);
        return View::fetch('/home/pay_talk');
    }

}