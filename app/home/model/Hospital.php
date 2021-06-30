<?php


namespace app\home\model;


use think\Model;


class Hospital extends Model
{
    protected $table = "hospital";


    /*
     * 获取医院下的医师
     */
    public static function GetDoctorParent($HospitalID)
    {
        $data = Hospital::where('ID', $HospitalID)->visible(['ID', 'HospitalName'])->find();
//        halt($data);
        return $data;
    }

}