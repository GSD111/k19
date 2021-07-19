<?php


namespace app\home\service;


use app\home\enum\StatusCode;
use app\home\model\UserOrder;
use app\home\model\UserTestOrder;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Log;
use think\facade\Session;


class Notify extends WxpayService
{
    public function notify()
    {
        $xml = $this->GetPostData();
        $arr = $this->XmlToArr($xml);

//        if ($this->checkSign($arr) && ($arr['return_code']=='SUCCESS') && ($arr['result_code']=='SUCCESS') ) {
//            $result = $arr;
//            // 这句file_put_contents是用来查看服务器返回的XML数据 测试完可以删除了
////            file_put_contents('app/home/log1.txt',$xml,FILE_APPEND);
////            Log::record()
//            //获取服务器返回的数据
//            $order_info = array(
//////                    'openId' => $arr['appid'],
//                    'OrderNo' => $arr['out_trade_no'], //订单单号
//                   'Money' => $arr['total_fee'] / 100, //付款金额
//                    "UserId" => Cache::get('user_id'), //当前用户的ID
//                    'PayTime' => $arr['time_end'], //支付时间
//                    'CreateTime' => time(),  //数据写入时间
//                    'Status' => StatusCode::USER_ORDER_STATUS_PAID  //修改支付状态
//                );
////            $order_sn = $arr['out_trade_no'];
////            $order_id = $arr['attach'];        //附加参数,选择传递订单ID
////            $openid = $arr['openid'];            //付款人openID
////            $total_fee = $arr['total_fee'];    //付款金额
//
//            //更新数据库
//            Log::record('交易成功');
//            UserTestOrder::create($order_info);//交易成功将支付信息写入表中;
//        }else{
//            $result = false;
//        }
        // 返回状态给微信服务器
//        $str='<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
//        echo $str;
//        if ($result) {
//            $str='<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
//        }else{
//            $str='<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名失败]]></return_msg></xml>';
//        }
//        echo $str;
//        return $result;

        if ($this->checkSign($arr)) {
            if ($arr['return_code'] == "SUCCESS" && $arr['result_code'] == "SUCCESS") {

                Log::record('交易成功');
//                if ($arr['total_fee'] == $arr['total_fee']) {    //根据订单号查询出订单金额进行验证
//                $order_info = array(
////                    'openId' => $arr['appid'],
//                    'OrderNo' => $arr['out_trade_no'],
//                    'Money' => $arr['total_fee'] / 100,
//                    "UserId" => Cache::get('user_id'),
//                    'PayTime' => $arr['time_end'],
//                    'CreateTime' => time(),
//                    'Status' => StatusCode::USER_ORDER_STATUS_PAID
//                );
//                Db::startTrans();
//                try {
//                    Log::record('交易成功');
//                    UserTestOrder::create($order_info);//交易成功将支付信息写入表中
//                    Db::commit();
//                    Cache::set('order_no',$arr['out_trade_no'],180);
////
//
//                } catch (\Exception $e) {
//                    Db::rollback();
//                    throw $e;
////                    halt($e->getMessage());
//
//                }
                //发送应答给商户平台
                $str = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
                echo $str;

                return $arr;

//                    echo $this->ArrToXml($result);
                // 返回状态给微信服务器

//                $str = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
//
//
//                echo $str;

            } else {
                Log::record('业务结果不正确');
                return '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[业务结果不正确]]></return_msg></xml>';

//                return "业务结果不正确";
            }
        } else {
            Log::record('签名验证失败');
            return '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名失败]]></return_msg></xml>';

//            return "签名验证失败";
        }

    }










//    public function talk_notify()
//    {
//        $xml = $this->GetPostData();
//        $arr = $this->XmlToArr($xml);
//
//        if ($this->checkSign($arr)) {
//            if ($arr['return_code'] == "SUCCESS" && $arr['result_code'] == "SUCCESS") {
////                if ($arr['total_fee'] == $arr['total_fee']) {    //根据订单号查询出订单金额进行验证
//                $order_info = array(
////                    'openId' => $arr['appid'],
//                    'OrderNo' => $arr['out_trade_no'],
//                    'Money' => $arr['total_fee'] / 100,
//                    'PayTime' => $arr['time_end'],
//                    'CreateTime' => time(),
//                    'Status' => StatusCode::USER_ORDER_STATUS_PAID
//                );
//                Db::startTrans();
//                try {
//                    Log::record('交易成功');
//                    Log::record(Session::get('users')['id']);
//                    UserOrder::where('UserID',Cache::get('user_id'))->save($order_info);//交易成功更新订单状态
////                    Db::table('usertestorder')->insert($order_info);   //交易成功将支付信息写入表中
//                    Db::commit();
//                    Cache::set('order_no',$arr['out_trade_no'],600);
////                   Cookie::set('order_no',$arr['out_trade_no'],600);
////                   Session::set('order_no',$arr['out_trade_no']) ;
////                    return '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
//
//                } catch (\Exception $e) {
//                    Db::rollback();
//                    throw $e;
////                    halt($e->getMessage());
//
//                }
//                //发送应答给商户平台
//                $str = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
//                echo $str;
//                exit;
//
//            } else {
//                Log::record('业务结果不正确');
//                return '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[业务结果不正确]]></return_msg></xml>';
//
//            }
//        } else {
//            Log::record('签名验证失败');
//            return '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名失败]]></return_msg></xml>';
//
//        }
//
//    }



}



