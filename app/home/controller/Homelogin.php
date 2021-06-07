<?php


namespace app\home\controller;


use app\BaseController;
use think\facade\View;

class Homelogin extends BaseController
{
    public function Login()
    {

        return View::fetch('home/login');
    }
}