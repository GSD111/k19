<?php

namespace app\home\service;

use app\home\model\User as UserModel;
use app\home\enum\StatusCode;

class User
{

    /*
     * 首页推荐的医生
     */
    public static function RecommendDoctor()
    {

        $user = UserModel::Where('IsDoctor', StatusCode::USER_DOCTOR)
            ->limit(4)
            ->orderRaw('rand()')
            ->select();
        if ($user->isEmpty()) {
            return false;
        }
        return $user;
    }

    /*
     * 首页推荐的医院
     */
    public static function RecommendHospital()
    {
        $hospital = UserModel::where('IsPersion', StatusCode::USER_PERSION)
//            ->where('IsDoctor', StatusCode::USER_ISDOCTOR)
            ->limit(4)
            ->orderRaw('rand()')
            ->select();

        if ($hospital->isEmpty()) {
            return false;
        }
        return $hospital;
    }

    /*
     * 获取所有的医生
     */

    public static function GetDoctorAll()
    {
        $data = UserModel::where('IsDoctor', StatusCode::USER_DOCTOR)->select();
        if ($data->isEmpty()) {
            return false;
        }

        return $data;
    }

    /*
     * 根据区域获取对应的医生
     * @param string $AreaId 区域的ID
     */
    public static function SearchAreaDoctor($AreaId)
    {
        $data = UserModel::where('IsDoctor', StatusCode::USER_DOCTOR)
            ->where('AreaId', $AreaId)->select();

        return $data;
    }

    /*
     * 获取所有的医院商家
     */

    public static function GetHospitalAll()
    {
        $data = UserModel::where('IsPersion', StatusCode::USER_PERSION)->select();
        if ($data->isEmpty()) {
            return false;
        }

        return $data;
    }

    /*
     * 根据区域获取对应的医院
     * @param string $AreaId 区域的ID
     */
    public static function SearchAreaHospital($AreaId)
    {
        $data = UserModel::where('IsPersion', StatusCode::USER_PERSION)
            ->where('AreaId', $AreaId)->select();

        return $data;
    }
}