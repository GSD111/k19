<?php


namespace app\home\controller;


use app\BaseController;
use app\home\enum\StatusCode;
use app\home\model\AreaCity as AreaCityModel;
use app\home\model\HospitalApply;
use app\home\service\User as UserService;
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
        $searchID = Request::param('id');
        $keywords = Request::param('city');

        if (!empty($searchID)) {
            $data = UserService::SearchAreaDoctor($searchID);
        } elseif (!empty($keywords)) {
            $data = AreaCityModel::GetAreaDoctor(StatusCode::USER_DOCTOR)->whereLike("CityName", $keywords);

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

    public function XlzxsArc($user_id)
    {
//       halt($user_id);
        $DoctorDetail = HospitalApply::GetDoctorDetail($user_id);
//        halt($DoctorDetail);
        $DoctorDetail['Specialty'] = json_decode($DoctorDetail['Specialty'] );
        $ParentName = HospitalApply::where('UserId', $DoctorDetail['HospitalID'])->visible(['Name', 'UserId'])->find();
        if ($DoctorDetail['HospitalID'] == 0) {
            $ParentName = '';
        }

//        halt($DoctorDetail);
        View::assign('DoctorDetail', $DoctorDetail);
        View::assign('ParentName', $ParentName);

        return View::fetch('home/xlzxs_arc');
    }
}