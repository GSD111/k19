<?php


namespace app\home\controller;


use app\BaseController;
use app\home\enum\StatusCode;
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
            $data = AreaCityModel::GetAreaDoctorOrHospital(StatusCode::USER_PERSION)->whereLike("CityName", $keywords);

        }else{
            $data = UserService::GetHospitalAll();
        }

        $area = AreaCityModel::GetAreaAll();
        $hotcity = AreaCityModel::IsHotCity()->toArray();
//        halt($hotcity);
        View::assign('keywords',$keywords);
        View::assign('area',$area);
        View::assign('hotcity',$hotcity);
        View::assign('data', $data);

        return View::fetch('home/hosp_list');
    }

    public function HospArc()
    {

        return View::fetch('home/hosp_arc');
    }

}