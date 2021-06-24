<?php


namespace app\home\model;


use think\Model;

class UserTest extends Model
{
     protected $table = "usertest";

     protected $type = [
         'CreateTime' => 'timestamp'
     ];
}