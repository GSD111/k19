<?php

namespace app\home\controller;

use app\BaseController;
use app\home\model\Article;
use app\home\model\Article as ArticleModel;
use app\home\model\ArticleType;
use app\home\model\FrontMenu;
use app\home\model\FrontMenu as FrontMenuModel;
use app\home\model\News;
use app\home\service\Article as ArticleService;
use app\home\service\FontMenu as FrontMenuService;
use app\home\service\User as UserService;
use think\facade\Request;
use think\facade\View;

class Index extends BaseController
{

    /*
     * 首页
     */
    public function Index()
    {

//        halt(Cache::get('users')['id'],
//            Cache::get('users')['phone'],Cache::get('users')['type']);
        //头部导航栏目按钮
        $result = FrontMenuModel::TopMenu();

        $doctor = UserService::RecommendDoctor();

        $hospital = UserService::RecommendHospital();
        $article = ArticleModel::RecommendArticle();
//        halt($article);
        $bottommenu = FrontMenu::BottomMenu();

        View::assign('result', $result);
        View::assign('doctor', $doctor);
        View::assign('hospital', $hospital);
        View::assign('article', $article);
        View::assign('bottommenu', $bottommenu);
//        halt($bottommenu);
        return View::fetch('home/index');
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

    public function XlcsList($id)
    {
        $data = FrontMenuService::GetTypeListInfo($id);
//        halt($data);
        return View::fetch('home/xlcs_list', ['data' => $data]);

    }

    public function ZsxgList()
    {
        $TypeId = Request::param('type_id');
        $keywords = Request::param('keywords');
        $ArticleType = ArticleType::GetArticleType();

        if (empty(!$TypeId)) {
            $ArticleAll = Article::SearchArticle($TypeId);
        } elseif (empty(!$keywords)) {
            $ArticleAll = Article::SearchKeywords($keywords);
        } else {
            $ArticleAll = Article::GetArticleAll();
        }

        View::assign('ArticleType', $ArticleType);
        View::assign('ArticleAll', $ArticleAll);
        return View::fetch('home/zsxg_list');
    }

    public function ZsxgList2()
    {
//        ArticleType::GetArticleType();
        return View::fetch('home/zsxg_list2');
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

//    public function XlzxsArc()
//    {
//
//        return View::fetch('home/xlzxs_arc');
//    }

    public function ArcArc($id)
    {
        $article = ArticleService::GetArticleDetail($id);
//        halt($article);
        View::assign('article', $article);

        return View::fetch('home/arc_arc');
    }

    public function JsjbList()
    {

        return View::fetch('home/jsjb_list');
    }



    public function ZlzxDh()
    {
        return View::fetch('home/xlzx_dh');
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


}
