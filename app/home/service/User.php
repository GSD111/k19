<?php

namespace app\home\service;

use app\home\enum\StatusCode;
use app\home\model\User as UserModel;
use think\facade\Db;

class User
{

    public static function GetIsRecommendAll()
    {
        $IsRecommendAll = Db::table('user')
            ->join('hospitalapply', 'user.ID = hospitalapply.UserId')
            ->where('Status', StatusCode::HOSPITAL_APPLY_SUCCESS)
            ->where('IsRecommend', StatusCode::USER_RECOMMEND)
            ->visible(['Name', 'UserAvatar', 'UserId', 'AreaId', 'IsDoctor', 'IsRecommend', 'Status','IsPersion','RealName'])
            ->select();

        return $IsRecommendAll;
    }

    /*
     * 首页推荐的医生
     */
    public static function RecommendDoctor()
    {

//        $user = UserModel::Where('IsDoctor', StatusCode::USER_DOCTOR)
//            ->limit(4)
//            ->orderRaw('rand()')
//            ->select();

        $user = self::GetIsRecommendAll() ->where('IsDoctor', StatusCode::USER_DOCTOR);
//        halt($user);
//        $user = Db::table('user')
//            ->join('hospitalapply', 'user.ID = hospitalapply.UserId')
//            ->where('Status', StatusCode::HOSPITAL_APPLY_SUCCESS)
//            ->where('IsDoctor', StatusCode::USER_DOCTOR)
//            ->where('IsRecommend', StatusCode::USER_RECOMMEND)
//            ->visible(['Name', 'UserAvatar', 'UserId', 'AreaId', 'IsDoctor', 'IsRecommend', 'Status'])
//            ->select();
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

        $hospital = self::GetIsRecommendAll()->where('IsPersion', StatusCode::USER_PERSION);

//        $hospital = Db::table('user')
//            ->join('hospitalapply', 'user.ID = hospitalapply.UserId')
//            ->where('Status', StatusCode::HOSPITAL_APPLY_SUCCESS)
//            ->where('IsPersion', StatusCode::USER_PERSION)
//            ->where('IsRecommend', StatusCode::USER_RECOMMEND)
//            ->visible(['Name', 'UserAvatar', 'UserId', 'AreaId', 'IsPersion', 'IsRecommend', 'Status'])
//            ->limit(4)
////            ->orderRaw('rand()')
//            ->select();
//        $hospital = UserModel::where('IsPersion', StatusCode::USER_PERSION)
////            ->where('IsDoctor', StatusCode::USER_ISDOCTOR)
//            ->limit(4)
//            ->orderRaw('rand()')
//            ->select();
//        halt($hospital);
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
        $data = Db::table('user')->join('hospitalapply', 'user.ID = hospitalapply.UserId')
            ->where('Status', StatusCode::HOSPITAL_APPLY_SUCCESS)
            ->visible(['Name', 'UserAvatar', 'Province', 'City', 'Area', 'Address', 'BusinessTime', 'UserId', 'AreaId', 'RealName'])
            ->select();
//        halt($data);
//        $data = UserModel::where('IsPersion', StatusCode::USER_PERSION)->select();
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
//        $data = UserModel::where('IsPersion', StatusCode::USER_PERSION)
//            ->where('AreaId', $AreaId)->select();

        $data = Db::table('user')->join('hospitalapply', 'user.ID = hospitalapply.UserId')
            ->where('Status', StatusCode::HOSPITAL_APPLY_SUCCESS)
            ->where('AreaId', $AreaId)
            ->visible(['Name', 'UserAvatar', 'Province', 'City', 'Area', 'Address', 'BusinessTime', 'UserId', 'AreaId', 'RealName'])
            ->select();
//        halt($data);
        return $data;
    }
}