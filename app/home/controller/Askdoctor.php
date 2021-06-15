<?php


namespace app\home\controller;


use app\BaseController;
use app\home\model\AreaCity as AreaCityModel;
use app\home\service\User as UserService;
use think\facade\Cache;
use think\facade\Request;
use think\facade\View;
use app\home\enum\StatusCode;

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
            $data = AreaCityModel::GetAreaDoctorOrHospital(StatusCode::USER_DOCTOR)->whereLike("CityName", $keywords);

        }else{
            $data = UserService::GetDoctorAll();
        }


        $area = AreaCityModel::GetAreaAll()->toArray();
//        halt($area);
//        $hotcity = AreaCityModel::IsHotCity()->toArray();

        View::assign('keywords',$keywords);
        View::assign('area',$area);
//        View::assign('hotcity',$hotcity);
        View::assign('data', $data);
        return View::fetch('home/xlzxs_list');

    }

    public function XlzxsArc($id)
    {

//        dd($id);

        return View::fetch('home/xlzxs_arc');
    }
}