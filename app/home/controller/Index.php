<?php

namespace app\home\controller;

use app\BaseController;
use app\home\model\FrontMenu;
use app\home\model\FrontMenu as FrontMenuModel;
use app\home\service\FontMenu as FrontMenuService;
use think\facade\View;

class Index extends BaseController
{

    /*
     * 首页
     */
    public function Index()
    {

        $result = FrontMenuModel::TopMenu();
//        halt($result);
        return View::fetch('home/index', ['result' => $result]);
    }

    public function Xlzx($id)
    {
        $result = FrontMenuService::IsChildMenu($id);
//        halt($result);
        return View::fetch('home/xlzx', ['result' => $result]);
    }

    public function XlzxQt($id)
    {
        $result = FrontMenuService::IsChildMenu($id);
//        halt($result);
        return View::fetch('home/xlzx_qt', ['result' => $result]);
    }

    public function Zczx($id)
    {
        $result = FrontMenuModel::GetChildMenu($id);
        return View::fetch('home/zczx', ['result' => $result]);
    }

    public function ZsxgList()
    {

        return View::fetch('home/zsxg_list');
    }

    public function WzxqsList()
    {
        return View::fetch('home/wzxqs_list');
    }

    public function WzxqsArc()
    {

        return View::fetch('home/wzxqs_arc');
    }

    public function WzxqsQs()
    {
        return View::fetch('home/wzxqs_qs');
    }

    public function XlzxsArc()
    {

        return View::fetch('home/xlzxs_arc');
    }

    public function ArcArc()
    {
        return View::fetch('home/arc_arc');
    }

    public function JsjbList()
    {

        return View::fetch('home/jsjb_list');
    }

    public function Gash()
    {

        return View::fetch('home/gash');
    }

    public function GashZzjg()
    {
        return View::fetch('home/gash_zzjg');
    }

    public function GashZx()
    {
        return View::fetch('home/gash_zx');
    }

    public function ZlzxDh()
    {
        return View::fetch('home/xlzx_dh');
    }


    public function XlcsList()
    {
        return View::fetch('home/xlcs_list');

    }

    public function XlcsArc()
    {
        return View::fetch('home/xlcs_arc');
    }

    public function Ylcs01()
    {

        return View::fetch('home/ylcs01');
    }

    public function Ylcs02()
    {

        return View::fetch('home/ylcs02');
    }

    public function Ylcs03()
    {

        return View::fetch('home/ylcs03');
    }

    public function Ylcs04()
    {

        return View::fetch('home/ylcs04');
    }

//    public function footer(){
//
//        return View::fetch('home/footer');
//    }
}
