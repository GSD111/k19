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
            ->where('IsDoctor', StatusCode::USER_ISDOCTOR)
            ->limit(4)
            ->orderRaw('rand()')
            ->select();

        if ($hospital->isEmpty()) {
            return false;
        }
        return $hospital;
    }
}