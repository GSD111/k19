<?php


namespace app\home\model;


use think\Model;

class HospitalApply extends Model
{
    protected $table = 'hospitalapply';
    protected $type = [
        'CreateTime' => 'timestamp'
    ];

    public static function GetApplyInfo($id){

        return HospitalApply::where('ID',$id)->find();
    }
}