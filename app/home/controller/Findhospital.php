<?php


namespace app\home\controller;


use app\BaseController;
use think\facade\View;
use app\home\service\User as UserService;

class Findhospital extends BaseController
{

    /*
     * 找医院
     */
    public function HospList()
    {
        $data = UserService::GetHospitalAll();
        View::assign('data', $data);

        return View::fetch('home/hosp_list');
    }

    public function HospArc()
    {

        return View::fetch('home/hosp_arc');
    }

}