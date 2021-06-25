<?php

namespace app\home\controller;

use app\BaseController;
use app\home\enum\StatusCode;
use app\home\model\Article;
use app\home\model\Article as ArticleModel;
use app\home\model\ArticleType;
use app\home\model\FrontMenu;
use app\home\model\FrontMenu as FrontMenuModel;
use app\home\model\UserTest;
use app\home\service\Article as ArticleService;
use app\home\service\FontMenu as FrontMenuService;
use app\home\service\User as UserService;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Index extends BaseController
{

    /*
     * 首页
     */
    public function Index()
    {


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

        $sex = Request::param('sex');
        if(!empty($sex)){
            $data = UserService::DoctorTalk()->where('Sex',$sex)->toArray();
//            halt($data);
        }else{
            $data = UserService::DoctorTalk()->toArray();
        }

        foreach ($data as $k => $v) {
            $data[$k]['Specialty'] = json_decode($v['Specialty']);
        }

//        halt($data);

//        $args['Specialty'] = empty($args['Specialty']) ? [] : explode(',', $args['Specialty']);
        $good_field = UserService::GetGoodField();

        View::assign('good_field', $good_field);
        View::assign('data', $data);


        return View::fetch('home/wzxqs_list');
    }

    public function WzxqsArc($id)
    {
        $info = UserService::DoctorTalk()->where('UserId', $id)->first();
        $info['Specialty'] = json_decode($info['Specialty']);
//        halt($info);

        View::assign('info', $info);
        return View::fetch('home/wzxqs_arc');
    }

    public function WzxqsQs($id)
    {
        $user_avatar = Request::param('user_avatar');
        $name = Request::param('name');
//        halt($name);
        $info = UserService::DoctorTalkDetail($id)->toArray();
        $info['Specialty'] = json_decode($info['Specialty']);

        $DoctorPrice = UserService::DoctorPriceList($id);

        View::assign('info', $info);
        View::assign('DoctorPrice', $DoctorPrice);
        View::assign('name', $name);
        View::assign('user_avatar', $user_avatar);

        return View::fetch('home/wzxqs_qs');
    }

    public function GetUserTalkOrder()
    {
//       halt(Request::param('doctor_price_id'),Request::param());
        $data = file_get_contents("php://input");
        $info = json_decode($data, true);
        halt($info);
        //根据前端传递的支付类别请求对应的支付接口
        //调用相应支付接口
        //支付成功将订单信息存进订单表，修改订单状态
        //根据订单状态来给对应的医生发送通知短信
    }

//    public function XlzxsArc()
//    {
//
//        return View::fetch('home/xlzxs_arc');
//    }


    public function ArcArc($id)
    {
//        halt(Cache::get('users')['id']);
        $user_id = Cache::get('users')['id'];
//        halt($user_id);
        $article = ArticleService::GetArticleDetail($id);
//        halt($article);
        $article_message = ArticleService::GetUserArticleMessage($id);
//        halt($article_message);
        ArticleService::UserArticleRecord($user_id, $id);
        ArticleService::ArticleReadNum($id);
//        halt($article);
        View::assign('article', $article);
        View::assign('article_message', $article_message);

        return View::fetch('home/arc_arc');
    }


    public function userMessage()
    {

//        halt(Request::param());
        $data = Db::table('articlemessage')->save([
            'UserID' => Cache::get('users')['id'],
            'ArticleID' => Request::param('article_id'),
            'MessageContent' => Request::param('message_content'),
            'IsAnonymous' => Request::param('is_anonymous'),
            'CreateTime' => time()
        ]);
        if ($data) {
            return "<script>alert('评论成功');window.history.back();</script>";
        }
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
        $type_id = Request::param('id');
//        halt($type_id);
        View::assign('type_id', $type_id);
        return View::fetch('home/xlcs_arc');
    }

    public function Ylcs01()
    {
//        halt(Request::param('type_id'));
//        $data = Db::table('question')
//            ->where('TestMenuID',Request::param('type_id'))
//            ->where('TestType','免费')
//            ->select()->toArray();
        $data = UserService::UserTestQuestion(Request::param('type_id'), StatusCode::USER_TEST_FREE)->toArray();
        foreach ($data as $k => $v) {
            $data[$k]['Select'] = json_decode($v['Select']);
        }
//        halt($data);
        if (empty($data)) {
            return "<script>alert('当前分类下暂时没有题!');window.history.back();</script>";
        }
        View::assign('data', $data);
        return View::fetch('home/ylcs01');
    }

    public function Ylcs02()
    {

        return View::fetch('home/ylcs02');
    }

    public function Ylcs03()
    {
//        halt(Request::param());
        $user_test = Request::param('user_test_id');
        $data = UserService::UserTestQuestion(Request::param('type_id'), StatusCode::USER_TEST_PAY)->toArray();
        foreach ($data as $k => $v) {
            $data[$k]['Select'] = json_decode($v['Select']);
        }

//        halt($data);
        if (empty($data)) {
            return "<script>alert('当前分类下暂时没有题!');window.history.back();</script>";
        }

        View::assign('data', $data);
        View::assign('user_test', $user_test);

        return View::fetch('home/ylcs03');
    }

    public function UserTest(){
//        halt(Request::param());
        $type_id = Request::param('type_id');
        $result = UserTest::create([
            'UserName'=>Request::param('user_name'),
            'Sex'=>Request::param('sex'),
            'Age'=>Request::param('age'),
            "UserEmail"=>Request::param('user_email'),
            "UserWechat"=>Request::param('user_wechat'),
            'Marriage'=>Request::param('marriage'),
            'Job'=>Request::param('job'),
            "Remark"=>Request::param('remark'),
            'UserId'=>Cache::get('users')['id'],
            'CreateTime'=>time()
        ]);
//
        return redirect('/home/ylcs03?type_id='.$type_id.'&user_test_id='.$result->id);
    }
    public function QuestionRecord()
    {
//        halt(Request::param());
//        halt(Cache::get('users')['id']);
        $data = file_get_contents("php://input");
        $result = json_decode($data,true);
        halt($result);
        foreach ($result['arr'] as $k=>$v){
             $info = Db::table('answerrecord')->insert([
                 'TestTitle'=>$v['title'],
                 'TestSelect'=>$v['select'],
                 'UserID'=>Cache::get('users')['id'],
                 'UserTestID'=>Request::param('user_test'),
                 'CreateTime'=>time()
             ]);
        }

        if($info){
            return $info=['code'=>200,'msg'=>'提交成功'];
        }else{
            return $info=['code'=>405,'msg'=>'提交失败'];
        }


    }

    public function Ylcs04()
    {

        return View::fetch('home/ylcs04');
    }


}
