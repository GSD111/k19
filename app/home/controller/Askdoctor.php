<?php


namespace app\home\controller;


use app\BaseController;
use app\home\enum\StatusCode;
use app\home\model\AreaCity as AreaCityModel;
use app\home\model\Hospital;
use app\home\model\HospitalApply;
use app\home\service\User as UserService;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Askdoctor extends BaseController
{

    /*
     * 问医生
     */
    public function XlzxsList()
    {

//        halt(Cache::get('users'));
        $city_name = Request::param('city_name');
        $keywords = Request::param('city');

        if (!empty($city_name)) {
            $data = UserService::SearchAreaDoctor($city_name);
        } elseif (!empty($keywords)) {
//            $data = AreaCityModel::GetAreaDoctor(StatusCode::USER_DOCTOR)->whereLike("CityName", $keywords);
            $data = UserService::GetDoctorAll()->whereLike('Province',$keywords);

        } else {
            $data = UserService::GetDoctorAll();
//            halt($data);
        }


        $area = AreaCityModel::GetAreaAll()->toArray();
//        halt($area);
//        $hotcity = AreaCityModel::IsHotCity()->toArray();

        View::assign('keywords', $keywords);
        View::assign('area', $area);
//        View::assign('hotcity',$hotcity);
        View::assign('data', $data);
        return View::fetch('home/xlzxs_list');

    }


    /*
     * 医生详情页
     * @parmas string $user_id  医生的id
     */
    public function XlzxsArc($user_id)
    {
        $users_id = Cache::get('users')['id'];
        $DoctorDetail = HospitalApply::GetDoctorDetail($user_id);
//        halt($DoctorDetail);
        if(empty($DoctorDetail)){
            return "<script>alert('该医师的信息还未完善快去完善吧！');window.history.back();</script>";
        }
        $DoctorDetail['Specialty'] = json_decode($DoctorDetail['Specialty']);
        $good_field = UserService::GetGoodField();

        if($DoctorDetail['HospitalID'] != 0){
            $ParentName = Hospital::GetDoctorParent($user_id);
        }else{
            $ParentName = '';
        }

//        $ParentName = HospitalApply::where('UserId', $DoctorDetail['HospitalID'])->visible(['Name', 'UserId'])->find();

        $result = Db::table('userfollow')->where('UserID',Cache::get('users')['id'])
            ->where('Doctor',$user_id)
            ->find();
//        halt($result);

//        halt($DoctorDetail);
        View::assign('good_field', $good_field);
        View::assign('users_id', $users_id);
        View::assign('ParentName', $ParentName);
        View::assign('result',$result);
        View::assign('DoctorDetail', $DoctorDetail);


        return View::fetch('home/xlzxs_arc');
    }


    public function GetFollow($user_id)
    {

        $UserId = Cache::get('users')['id'];
        $info = UserService::UserFollowDoctor($UserId,$user_id);
        if ($info == true) {

            return $data = ['code' => 200, 'msg' => '关注成功'];
        } else {
            return $data = ['code' => 405, 'msg' => '关注失败'];
        }
    }

}