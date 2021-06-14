<?php


namespace app\home\controller;


use app\BaseController;
use app\home\model\HospitalApply;
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

    public function GrzxJgrztj($id)
    {

//        halt($id);
        $result = HospitalApply::GetApplyInfo($id);
        View::assign('result',$result);
        return View::fetch('home/grzx_jgrztj');
    }

    public function GrzxYsrztj($id)
    {

        $info = HospitalApply::GetApplyInfo($id);
//        halt($result->toArray());
        View::assign('info',$info);
        return View::fetch('home/grzx_ysrztj');

    }
}