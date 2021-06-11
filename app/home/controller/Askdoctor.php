<?php


namespace app\home\controller;


use app\BaseController;
use app\home\model\AreaCity as AreaCityModel;
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

        $keywords = Request::param('city');
        if(empty($keywords)){
            $data = UserService::GetDoctorAll();
        }else{
            $data = AreaCityModel::GetAreaDoctorOrHospital()->whereLike("CityName",$keywords);
        }
        halt($data);
        $area = AreaCityModel::GetAreaAll();
        $hotcity = AreaCityModel::IsHotCity()->toArray();

        View::assign('area',$area);
        View::assign('hotcity',$hotcity);
        View::assign('data', $data);
        return View::fetch('home/xlzxs_list');

    }

    public function XlzxsArc()
    {

        return View::fetch('home/xlzx_arc');
    }
}