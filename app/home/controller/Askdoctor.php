<?php


namespace app\home\controller;


use app\BaseController;
use think\facade\View;
use app\home\service\User as UserService;

class Askdoctor extends BaseController
{

    /*
     * 问医生
     */
    public function XlzxsList()
    {
        $data = UserService::GetDoctorAll();

        View::assign('data', $data);
        return View::fetch('home/xlzxs_list');

    }

    public function XlzxsArc()
    {

        return View::fetch('home/xlzx_arc');
    }
}