<?php


namespace app\home\model;


use think\facade\Db;
use think\Model;

class UserTestOrder extends Model
{
    protected $table = "usertestorder";

    protected $type = [
        "CreateTime" => 'timestamp'
    ];


    /*
     * 获取用户专业测试订单的订单信息
     * @params string $user_id  用户的id
     * @params string $status  订单的状态
     */
    public static function GetUserTestOrderInfo($user_id,$status){

//        $data = UserTestOrder::where('UserId',$user_id)->where('Status',$status)->order('CreateTime desc')->select();

        $data = Db::table('usertest')
            ->join('usertestorder', 'usertestorder.OrderNo = usertest.OrderNo')
            ->field('usertestorder.OrderNo order_no,usertestorder.Status,usertestorder.Money,usertestorder.ID order_id,usertest.*')
            ->where('usertest.UserId',$user_id)
            ->where('Status',$status)
            ->order('CreateTime desc')->select();

        return $data;
    }
}