<?php


namespace app\home\model;


use think\Model;

class UserOrder extends Model
{
    protected $table = 'userorder';

    protected $type = [
        "CreateTime" => 'timestamp'
    ];

    /*
     * 获取用户的订单信息
     * @params string $user_id  用户的id
     * @params string $status  订单的状态
     */
    public static function GetUserOrderInfo($user_id,$status){

        $data = UserOrder::where('UserID',$user_id)->where('Status',$status)->order('CreateTime desc')->select();

        return $data;
    }
}