<?php


namespace app\home\controller;


use app\BaseController;
use think\facade\Request;
use think\facade\View;

class Personalcenter extends BaseController
{

    /*
     * 个人中心
     */
    public function GrzxMain()
    {

        return View::fetch('home/grzx_main');
    }

    public function GrzxWdzx()
    {

        return View::fetch('home/grzx_wdzx');
    }

    public function GrzxJypj()
    {

        return View::fetch('home/grzx_jypj');
    }

    public function GrzxWdgz()
    {

        return View::fetch('home/grzx_wdgz');
    }

    public function GrzxJgrz()
    {

        return View::fetch('home/grzx_jgrz');
    }

    public function GrzxYsrz()
    {
        return View::fetch('home/grzx_ysrz');
    }

    public function GrzxXtsz()
    {

        return View::fetch('home/grzx_xtsz');
    }

    public function SjzxMain()
    {
        return View::fetch('home/sjzx_main');
    }

    public function GrzxCsjg()
    {
        return View::fetch('home/grzx_csjg');
    }

    public function GrzxCspj()
    {
        return View::fetch('home/grzx_cspj');
    }

    public function GrzxZxspj()
    {

        return View::fetch('home/grzx_zxspj');
    }

    public function GrzxJgrztj()
    {
        return View::fetch('home/grzx_jgrztj');
    }

    public function GrzxYsrztj()
    {
        return View::fetch('home/grzx_ysrztj');

    }
}