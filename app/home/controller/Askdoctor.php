<?php


namespace app\home\controller;


use app\BaseController;
use think\facade\View;

class Askdoctor extends BaseController
{

    /*
     * 问医生
     */
    public function XlzxsList()
    {

        return View::fetch('home/xlzxs_list');

    }

    public function XlzxsArc()
    {

        return View::fetch('home/xlzx_arc');
    }
}