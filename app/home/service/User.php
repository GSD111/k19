<?php

namespace app\home\service;

use app\home\enum\StatusCode;
use app\home\model\User as UserModel;
use think\facade\Db;

class User
{

//    public static function GetIsRecommendAll()
//    {
//        $IsRecommendAll = Db::table('user')
//            ->join('hospitalapply', 'user.ID = hospitalapply.UserId')
//            ->where('Status', StatusCode::HOSPITAL_APPLY_SUCCESS)
//            ->where('IsRecommend', StatusCode::USER_RECOMMEND)
//            ->visible(['Name', 'UserAvatar', 'UserId', 'AreaId', 'IsDoctor',
//                'IsRecommend', 'Status','IsPersion','RealName','HospitalID'])
//            ->limit(4)
//            ->select();
//
//        return $IsRecommendAll;
//    }

    /*
     * 首页推荐的医生
     */
    public static function RecommendDoctor()
    {

//        $user = self::GetIsRecommendAll()->where('IsDoctor', StatusCode::USER_DOCTOR);
        $user = Db::table('user')
        ->join('hospitalapply', 'user.ID = hospitalapply.UserId')
        ->where('Status', StatusCode::HOSPITAL_APPLY_SUCCESS)
        ->where('IsRecommend', StatusCode::USER_RECOMMEND)
        ->where('IsDoctor', StatusCode::USER_DOCTOR)
        ->visible(['Name', 'UserAvatar', 'UserId', 'AreaId', 'IsDoctor',
            'IsRecommend', 'Status', 'IsPersion', 'RealName', 'HospitalID'])
        ->limit(4)
        ->select();
//        halt($user);
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

//        $hospital = self::GetIsRecommendAll()->where('IsPersion', StatusCode::USER_PERSION);

        $hospital = Db::table('user')
            ->join('hospitalapply', 'user.ID = hospitalapply.UserId')
            ->where('Status', StatusCode::HOSPITAL_APPLY_SUCCESS)
            ->where('IsRecommend', StatusCode::USER_RECOMMEND)
            ->where('IsPersion', StatusCode::USER_PERSION)
            ->visible(['Name', 'UserAvatar', 'UserId', 'AreaId', 'IsDoctor',
                'IsRecommend', 'Status', 'IsPersion', 'RealName', 'HospitalID'])
            ->limit(4)
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
        $data = self::GetDoctorAll()->where('AreaId', $AreaId);
//        $data = UserModel::where('IsDoctor', StatusCode::USER_DOCTOR)
//            ->where('AreaId', $AreaId)->select();

        return $data;
    }


    /*
     * 获取所有的医院商家
     */

    public static function GetHospitalAll()
    {

        $data = Db::table('user')->join('hospitalapply', 'user.ID = hospitalapply.UserId')
            ->where('Status', StatusCode::HOSPITAL_APPLY_SUCCESS)
            ->where('IsPersion', StatusCode::USER_PERSION)
            ->visible(['Name', 'UserAvatar', 'Province',
                'City', 'Area', 'Address', 'BusinessTime',
                'UserId', 'AreaId', 'RealName', 'IsPersion'])
            ->select();
        if ($data->isEmpty()) {
            return false;
        }
//
        return $data;
    }

    /*
     * 根据区域获取对应的医院
     * @param string $AreaId 区域的ID
     */
    public static function SearchAreaHospital($AreaId)
    {
        $data = self::GetHospitalAll()->where('AreaId', $AreaId);

        return $data;
    }
}