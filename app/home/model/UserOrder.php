<?php


namespace app\home\model;


use think\Model;

class UserOrder extends Model
{
    protected $table = 'userorder';

    protected $type = [
        "CreateTime" => 'timestamp'
    ];
}