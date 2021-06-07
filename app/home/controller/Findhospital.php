<?php


namespace app\home\controller;


use app\BaseController;
use think\facade\View;

class Findhospital extends BaseController
{

    /*
     * 找医院
     */
    public function HospList()
    {

        return View::fetch('home/hosp_list');
    }

    public function HospArc(){

        return View::fetch('home/hosp_arc');
    }

}