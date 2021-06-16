<?php


namespace app\home\controller;


use app\BaseController;
use app\home\enum\StatusCode;
use app\home\model\HospitalApply;
use think\facade\Request;
use think\facade\View;
use app\home\service\User as UserService;
use app\home\model\AreaCity AS AreaCityModel;

class Findhospital extends BaseController
{

    /*
     * 找医院
     */
    public function HospList()
    {

        $searchID = Request::param('id');
        $keywords = Request::param('city');
        if (!empty($searchID)) {
            $data = UserService::SearchAreaHospital($searchID);
        } elseif (!empty($keywords)) {
            $data = AreaCityModel::GetAreaHospital(StatusCode::USER_PERSION)->whereLike("CityName", $keywords);

        }else{
            $data = UserService::GetHospitalAll();
        }

        $area = AreaCityModel::GetAreaAll();
//        $hotcity = AreaCityModel::IsHotCity()->toArray();
//        halt($hotcity);
        View::assign('keywords',$keywords);
        View::assign('area',$area);
//        View::assign('hotcity',$hotcity);
        View::assign('data', $data);

        return View::fetch('home/hosp_list');
    }

    public function HospArc($user_id)
    {
//        halt($user_id);
        $HospitalDeatil = HospitalApply::GetHospitalDetail($user_id);
//        halt($HospitalDeatil);
        $HospitalDoctor = HospitalApply::GetHospitalDoctor($user_id);
//        halt($HospitalDoctor);

        View::assign('HospitalDetail',$HospitalDeatil);
        View::assign('HospitalDoctor',$HospitalDoctor);
        return View::fetch('home/hosp_arc');
    }

}