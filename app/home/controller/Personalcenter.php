<?php


namespace app\home\controller;


use app\BaseController;
use app\home\model\HospitalApply;
use think\facade\Cache;
use think\facade\View;

class Personalcenter extends BaseController
{

    /*
     * 个人中心
     */
    public function GrzxMain()
    {
        $user_phone = Cache::get('users')['phone'];
        $user_id = Cache::get('users')['id'];
        $user_type = Cache::get('users')['type'];

        $user = HospitalApply::where('ID', $user_id)->find();

        if ($user['Status'] == 1) {
            $status = '审核中';
        } elseif ($user['Status'] == 2) {
            $status = '审核完成';
        } elseif ($user['Status'] == 3) {
            $status = '驳回';
        } else {
            $status = '审核失败';
        }

        View::assign('user_phone', $user_phone);
        View::assign('user_id', $user_id);
        View::assign('user_type', $user_type);
        View::assign('user', $user);
        View::assign('status', $status);

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
        View::assign('result', $result);
        return View::fetch('home/grzx_jgrztj');
    }

    public function GrzxYsrztj($id)
    {

        $info = HospitalApply::GetApplyInfo($id);
        $info['Specialty'] = json_decode($info['Specialty']);
//        halt($info);
        View::assign('info', $info);
        return View::fetch('home/grzx_ysrztj');

    }

    public function LoginOut()
    {
        Cache::pull('users');

        redirect('/home/login')->send();
    }
}