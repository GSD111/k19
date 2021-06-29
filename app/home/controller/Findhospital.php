<?php


namespace app\home\controller;


use app\BaseController;
use app\home\model\AreaCity as AreaCityModel;
use app\home\model\HospitalApply;
use app\home\service\User as UserService;
use think\facade\Request;
use think\facade\View;

class Findhospital extends BaseController
{

    /*
     * 找医院
     */
    public function HospList()
    {

        $city_name = Request::param('city_name');
//        dump(Request::param('city_name'));
        $keywords = Request::param('city');
        if (!empty($city_name)) {
            $data = UserService::SearchAreaHospital($city_name);

        } elseif (!empty($keywords)) {
//            $data = AreaCityModel::GetAreaHospital()->whereLike("CityName", $keywords);
            $data = UserService::GetHospitalAll()->whereLike("pro", $keywords);

        } else {
            $data = UserService::GetHospitalAll();
//            halt($data);
        }

        $area = AreaCityModel::GetAreaAll();
//        $hotcity = AreaCityModel::IsHotCity()->toArray();
//        halt($hotcity);
        View::assign('keywords', $keywords);
        View::assign('area', $area);
//        View::assign('hotcity',$hotcity);
        View::assign('data', $data);

        return View::fetch('home/hosp_list');
    }

    public function HospArc($user_id)
    {
//        halt($user_id);
        $HospitalDeatil = HospitalApply::GetHospitalDetail($user_id);
//        halt($HospitalDeatil);
        $HospitalDoctor = HospitalApply::GetHospitalDoctor($HospitalDeatil['ID']);
//        halt($HospitalDoctor);

        View::assign('HospitalDetail', $HospitalDeatil);
        View::assign('HospitalDoctor', $HospitalDoctor);
        return View::fetch('home/hosp_arc');
    }

}