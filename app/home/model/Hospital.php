<?php


namespace app\home\model;


use think\Model;


class Hospital extends Model
{
    protected $table = "hospital";


    public static function GetDoctorParent($HospitalID)
    {
        $data = Hospital::where('ID', $HospitalID)->visible(['ID', 'HospitalName'])->find();

        return $data;
    }
}