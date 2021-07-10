<?php

namespace app\home\controller;

use app\BaseController;
use app\home\enum\StatusCode;
use app\home\model\Article;
use app\home\model\Article as ArticleModel;
use app\home\model\ArticleType;
use app\home\model\FrontMenu;
use app\home\model\FrontMenu as FrontMenuModel;
use app\home\model\TypeList;
use app\home\model\UserOrder;
use app\home\model\UserTest;
use app\home\service\Article as ArticleService;
use app\home\service\FontMenu as FrontMenuService;
use app\home\service\User as UserService;
use app\home\service\WxpayService;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;


class Index extends BaseController
{

    /*
     * 首页
     */
    public function Index()
    {

//            Cache::get('users')['phone'],Cache::get('users')['type']);
//        halt(Session::get('user_id'),Session::get('phone'),Session::get('is_persion'),Session::get('aa'));
//        halt(Session::get('users')['id']);
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

//    //微信公众号验证接口
//    public function wxapi(){
//        $echoStr = $_GET["echostr"];//从微信用户端获取一个随机字符赋予变量echostr
//
//        if($this->checkSignature()){
//            echo $echoStr;
//            exit;
//        }
//    }
//
//    private function checkSignature()
//    {
//        $signature = $_GET["signature"];//从用户端获取签名赋予变量signature
//        $timestamp = $_GET["timestamp"];//从用户端获取时间戳赋予变量timestamp
//        $nonce = $_GET["nonce"];  //从用户端获取随机数赋予变量nonce
//
////        $token = config('tk.wx_token');//将常量token赋予变量token
//        $token = 'SaiGeR2021';//将常量token赋予变量token
//        $tmpArr = array($token, $timestamp, $nonce);//简历数组变量tmpArr
//        sort($tmpArr, SORT_STRING);//新建排序
//        $tmpStr = implode( $tmpArr );//字典排序
//        $tmpStr = sha1( $tmpStr );//shal加密
//        //tmpStr与signature值相同，返回真，否则返回假
//        if( $tmpStr == $signature ){
//            return true;
//        }else{
//            return false;
//        }
//    }


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
        $title = Request::param('title');
        $data = FrontMenuService::GetTypeListInfo($id);
//        halt($data);
        View::assign('data', $data);
        View::assign('title', $title);

        return View::fetch('home/xlcs_list');

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
        if (!empty($sex)) {
            $data = UserService::DoctorTalk()->where('Sex', $sex)->toArray();
//            halt($data);
        } else {
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
        $user_comment = UserService::GetDoctorCommentInfo($id);
//        halt($user_comment);
        View::assign('info', $info);
        View::assign('user_comment', $user_comment);
        return View::fetch('home/wzxqs_arc');
    }

    public function WzxqsQs($id)
    {
        $user_avatar = Request::param('user_avatar');
        $name = Request::param('name');
//        halt($name);
        $info = UserService::DoctorTalkDetail($id)->toArray();
//        halt($id);
        $info['Specialty'] = json_decode($info['Specialty']);

        $DoctorPrice = UserService::DoctorPriceList($id);

        View::assign('info', $info);
        View::assign('DoctorPrice', $DoctorPrice);
        View::assign('name', $name);
        View::assign('id', $id);
        View::assign('user_avatar', $user_avatar);

        return View::fetch('home/wzxqs_qs');
    }

    public function GetUserTalkOrder()
    {
//       halt(Request::param('doctor_price_id'),Request::param());
        $data = file_get_contents("php://input");
        $info = json_decode($data, true);
//        halt($info['phone_number']);
        $result = UserOrder::create([
            'UserID' => Session::get('users')['id'],
            'DoctorName' => $info['doctor_name'],
            'DoctorID' => $info['doctor_id'],
            'Time' => $info['times'],
            'Money' => $info['price'],
            "UserName" => $info['username'],
            'Phone' => $info['phone_number'],
            "PayWay" => $info['pay_way'],
            "CreateTime" => time()
        ]);

        return $result;
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
        $user_id = Session::get('users')['id'];
//        halt($user_id);
        $article = ArticleService::GetArticleDetail($id);
//        halt($article);
        $article_message = ArticleService::GetUserArticleMessage($id);
//        halt($article_message);
        ArticleService::UserArticleRecord($user_id, $id);
        ArticleService::ArticleReadNum($id);
//        halt($article);
        View::assign('user_id', $user_id);
        View::assign('article', $article);
        View::assign('article_message', $article_message);

        return View::fetch('home/arc_arc');
    }


    public function userMessage()
    {

//        halt(Request::param());
        $data = Db::table('articlemessage')->save([
            'UserID' => Session::get('users')['id'],
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
        $title = Request::param('title');
        $type_id = Request::param('id');
//        halt($type_id);
        $data = TypeList::GetTypeInfo($type_id);
//        halt($data);
        View::assign('data', $data);
        View::assign('type_id', $type_id);
        View::assign('title', $title);

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

    public function UserTest()
    {
//        halt(Request::param());
        $type_id = Request::param('type_id');
        $result = UserTest::create([
            'UserName' => Request::param('user_name'),
            'Sex' => Request::param('sex'),
            'Age' => Request::param('age'),
            "UserEmail" => Request::param('user_email'),
            "UserWechat" => Request::param('user_wechat'),
            'Marriage' => Request::param('marriage'),
            'Job' => Request::param('job'),
            "Remark" => Request::param('remark'),
            'UserId' => Cache::get('users')['id'],
            'CreateTime' => time()
        ]);
//
        return redirect('/home/ylcs03?type_id=' . $type_id . '&user_test_id=' . $result->id);
    }

    public function QuestionRecord()
    {
//        halt(Request::param());
//        halt(Cache::get('users')['id']);
        $data = file_get_contents("php://input");
        $result = json_decode($data, true);
//        halt($result['pay_way'],$result['user_test_id']);
        foreach ($result['arr'] as $k => $v) {
            $info = Db::table('answerrecord')->insert([
                'TestTitle' => $v['title'],
                'TestSelect' => $v['select'],
                'UserID' => Session::get('users')['id'],
                'UserTestID' => $result['user_test_id'],
                'CreateTime' => time()
            ]);
        }

        if ($info) {
            return $info = ['code' => 200, 'msg' => '提交成功'];
        } else {
            return $info = ['code' => 405, 'msg' => '提交失败'];
        }


    }

    public function Ylcs04()
    {

        return View::fetch('home/ylcs04');
    }


    public function PayTest()
    {




        $wxPay = new WxpayService();
//        $wxPay->getSign($arr);
//        $arr = $wxPay->setSign($arr);
//        print_r($arr);

//        if($wxPay->checkSign($arr)){
//            echo "签名验证成功";
//        }else{
//            echo "签名验证失败";
//        }

//        $openid = $wxPay->GetOpenid();
//        halt($openid);
//        halt($openid);
        $info = $wxPay->unifiedOrder('微信公众号测试',2);
//        p($info);
//        print_r($info['prepay_id']);
//         halt($wxPay->makeOrderNo());
//        $prepay_id = $wxPay->GetPrepayId();
//        $prepay_id = $wxPay->GetPrepayId();
//        halt($prepay_id);
        $data = $wxPay->GetJsParams($info['prepay_id']);
//        halt($data);
        View::assign('data', $data);
        return View::fetch('/home/paytest');

    }

//    function p()
//    {
//        $numargs = func_get_args();
//        foreach ($numargs as $v) {
//            if (request()->isCli()) {
//                print_r($v);
//                echo "\n";
//            } else {
//                echo "<pre>";
//                print_r($v);
//                echo "</pre>";
//                echo '<hr>';
//            }
//        }
//    }


    public function notify()
    {
        $notify = new WxpayService();
        $notify->notify();
        //1获取通知数据(原始数据格式为XML)->转换成数组
        //2验证签名
        //3验证业务结果(return_code 和 result_code)
        //4验证订单号和支付金额(out_trade_no 和 total_fee)
        //5记录日志 修改订单状态(然后根据自己的业务进行处理下一步操作)
    }


}
