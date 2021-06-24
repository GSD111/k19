<?php


namespace app\home\model;


use think\Model;

class User extends Model
{
    protected $table = "user";

    protected $readonly = ['ID','RealName','UserAvatar','AreaId'];



}