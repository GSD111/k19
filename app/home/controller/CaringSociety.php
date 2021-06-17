<?php


namespace app\home\controller;


use app\BaseController;
use app\home\model\News;
use think\facade\Request;
use think\facade\View;

class CaringSociety extends BaseController
{

    public function Gash()
    {
        $News = News::GetNewsList();

        View::assign('News', $News);
        return View::fetch('home/gash');
    }

    public function GashZx($id)
    {

        $data = News::GetByNewId($id);

        $info = News::RecommentNews();

        View::assign('data', $data);
        View::assign('info', $info);

        return View::fetch('home/gash_zx');
    }

    public function GashZzjg()
    {

        $keywords = Request::param('keywords');

        if (empty($keywords)) {
            $data = News::GetCharityList();
        } else {
            $data = News::SearchCharity($keywords);
        }


        View::assign('data', $data);
        return View::fetch('home/gash_zzjg');
    }


}