<?php

namespace app\home\service;

use app\home\enum\StatusCode;
use app\home\model\HospitalApply;
use app\home\model\User as UserModel;
use think\facade\Db;

class User
{


    /*
     * 首页推荐的医生
     */
    public static function RecommendDoctor()
    {

//        $user = self::GetIsRecommendAll()->where('IsDoctor', StatusCode::USER_DOCTOR);
        $user = Db::table('user')
            ->join('hospitalapply hosp', 'user.ID = hosp.UserId')
            ->field('user.ID UID,user.UserAvatar,user.RealName,user.IsRecommend,hosp.*')
            ->where('Status', StatusCode::HOSPITAL_APPLY_SUCCESS)
            ->where('IsRecommend', StatusCode::USER_RECOMMEND)
            ->where('IsDoctor', StatusCode::USER_DOCTOR)
            ->visible(['UID', 'UserAvatar', 'IsDoctor', 'IsRecommend', 'Status', 'RealName', 'Name'])
            ->limit(4)
            ->select();
//        halt($user);
//        if ($user->isEmpty()) {
//            return false;
//        }
        return $user;
    }

    /*
     * 首页推荐的医院
     */
    public static function RecommendHospital()
    {

//        $hospital = self::GetIsRecommendAll()->where('IsPersion', StatusCode::USER_PERSION);

        $hospital = Db::table('user')
            ->join('hospital hosp', 'user.HospitalID = hosp.ID')
//            ->where('Status', StatusCode::HOSPITAL_APPLY_SUCCESS)
            ->field('user.ID UID,user.UserAvatar,user.RealName,user.IsPersion,user.IsRecommend recommend,hosp.*')
            ->where('hosp.IsRecommend', StatusCode::USER_RECOMMEND)
            ->where('IsPersion', StatusCode::USER_PERSION)
            ->visible(['HospitalName', 'UserAvatar', 'IsRecommend', 'IsPersion', 'RealName', 'HospitalID', 'UID'])
            ->limit(4)
            ->select();
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
//        $data = UserModel::where('IsDoctor', StatusCode::USER_DOCTOR)->select();
        $data = DB::table('user')
            ->join('hospitalapply hosapply', 'user.ID = hosapply.UserId')
            ->field('user.RealName,user.HospitalID,user.UserAvatar,user.ID UID,user.Remark,user.IsDoctor,hosapply.*')
            ->where('hosapply.Status', StatusCode::HOSPITAL_APPLY_SUCCESS)
            ->where('user.IsDoctor', StatusCode::USER_DOCTOR)
            ->select();
//        halt($data->toArray());
        if ($data->isEmpty()) {
            return false;
        }

        return $data;
    }

    /*
     * 根据区域获取对应的医生
     * @param string $AreaId 区域的ID
     */
    public static function SearchAreaDoctor($city_name)
    {
        $data = self::GetDoctorAll()->where('Province', $city_name);
//        $data = UserModel::where('IsDoctor', StatusCode::USER_DOCTOR)
//            ->where('AreaId', $AreaId)->select();

        return $data;
    }


    /*
     * 获取所有的医院商家及用户信息
     */

    public static function GetHospitalAll()
    {
        $data = DB::table('user')
            ->join('hospital hosp', 'user.HospitalID = hosp.ID')
            ->join('hospitalapply hosapply', 'user.ID = hosapply.UserId')
            ->field('hosapply.Status,hosapply.Name,hosapply.BusinessTime,
             hosapply.Province pro ,hosapply.City city, hosapply.Area area,hosapply.Address,
             hosp.*,user.RealName,user.HospitalID,user.UserAvatar,user.ID UID')
            ->where('Status', StatusCode::HOSPITAL_APPLY_SUCCESS)
            ->where('IsPersion', StatusCode::USER_PERSION)
            ->select();
//        $data = Db::table('user')->join('hospitalapply', 'user.ID = hospitalapply.UserId')
//            ->where('Status', StatusCode::HOSPITAL_APPLY_SUCCESS)
//            ->where('IsPersion', StatusCode::USER_PERSION)
//            ->visible(['Name', 'UserAvatar', 'Province',
//                'City', 'Area', 'Address', 'BusinessTime',
//                'UserId', 'AreaId', 'RealName', 'IsPersion'])
//            ->select();
//        if ($data->isEmpty()) {
//            return false;
//        }
//          halt($data);
        return $data;
    }

    /*
     * 根据区域获取对应的医院
     * @param string $city_name 区域的ID
     */
    public static function SearchAreaHospital($city_name)
    {
        $data = self::GetHospitalAll()->where('pro', $city_name);

        return $data;
    }

    /*
     * 倾述一刻所有医生的信息
     */
    public static function DoctorTalk()
    {
        $data = Db::table('user')
            ->join('hospitalapply', 'user.ID = hospitalapply.UserId')
            ->join('usertalk', 'user.ID = usertalk.UserID')
            ->where('Status', StatusCode::HOSPITAL_APPLY_SUCCESS)
            ->where('IsDoctor', StatusCode::USER_DOCTOR)
            ->visible(['RealName', 'UserAvatar', 'IsDoctor', 'Sex', 'Remark', 'Qualification', 'Resume',
                'Signature', 'Name', 'Specialty', 'Status', 'UserId', 'ServiceNum', 'PraiseRate'])
            ->order('ServiceNum desc')
            ->select();

        return $data;

    }

    /*
     * 倾述一刻某一个医师的相关信息
     * @params string $id医师id
     */

    public static function DoctorTalkDetail($id)
    {
        $data = HospitalApply::where('UserId', $id)->visible(['Name', 'Specialty'])->find();

        return $data;
    }

    /*
     * 医师的价格信息
     * @params string $id医师id
     */
    public static function DoctorPriceList($id)
    {
        $data = Db::table('doctorprice')
            ->where('UserID', $id)
            ->visible(['ID', 'Money', 'Time', 'UserID'])->select();

        return $data;
    }


    /*
     * 用户关注医师
     */

    public static function UserFollowDoctor($user_id, $doctor_id)
    {
        $result = Db::table('userfollow')->where('UserID', $user_id)
            ->where('Doctor', $doctor_id)
            ->find();

        if (empty($result)) {
            $data = Db::table('userfollow')
                ->save([
                    'UserID' => $user_id,
                    'Doctor' => $doctor_id,
                    "CreateTime" => time()
                ]);
        } else {
            $data = Db::table('userfollow')->where('Doctor', $doctor_id)->save([
                'UpdateTime' => time(),
            ]);
        }

        return $data;

    }


    /*
     * 擅长领域
     */
    public static function GetGoodField()
    {
        return Db::table('goodfield')->where('IsDelete',0)->visible(['ID', 'Name'])->select();
    }


    /*
     * 用户测试题
     * @params string $type_id 测试分类的id
     * @params string $category 测试题的类别
     */
    public static function UserTestQuestion($type_id, $category)
    {
        $data = Db::table('question')
            ->where('TestMenuID', $type_id)
            ->where('TestType', $category)
            ->select();

        return $data;
    }



   /*
    * 倾诉一刻用户评价
    * @params string $id 医师的id
    */
    public static function GetDoctorCommentInfo($id){

        $data = Db::table('user')
            ->join('evaluate','user.ID = evaluate.UserID')
            ->field('user.RealName,evaluate.*')
            ->where('DoctorID',$id)
            ->order('CreateTime desc')
            ->limit(10)
            ->select();

        return $data;
    }


}