<?php


namespace app\home\controller;


use app\BaseController;
use app\home\enum\StatusCode;
use app\home\model\Hospital;
use app\home\model\HospitalApply;
use app\home\model\User;
use app\home\model\UserOrder;
use app\home\service\Article;
use app\home\service\User as UserService;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Filesystem;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;

class Personalcenter extends BaseController
{

    /*
     * 个人中心
     */
    public function GrzxMain()
    {
//        halt(Cache::get('users'));
//        $user_phone = Cache::get('users')['phone'];
//        $user_id = Cache::get('users')['id'];
//        $doctor = Cache::get('users')['doctor'];
//        $persion = Cache::get('users')['persion'];

        $user_phone = Session::get('users')['phone'];
        $user_id = Session::get('users')['id'];
        $user_login = User::where('ID', $user_id)->visible(['ID', 'IsPersion'])->find();
//        halt($user_login->toArray());
//        /*
//         * 检测当前登录者的身份信息
//         */
        if ($user_login['IsPersion'] == StatusCode::USER_PERSION) {
            $data = HospitalApply::GetApplayAll($user_id);
//            halt($data);
            $hospital_info = Hospital::where('ID', $data['HospitalID'])->visible(['ID', 'HospitalInfo'])->find();
//            halt($hospital_info);
            $HospitalDoctor = HospitalApply::GetHospitalDoctor($hospital_info['ID']);
//            halt($HospitalDoctor);
            View::assign('data', $data);
            View::assign('hospital_info', $hospital_info);
            View::assign('HospitalDoctor', $HospitalDoctor);
            return View::fetch('home/sjzx_main');
        }


//        $user = HospitalApply::where('ID', $user_id)->find();
        $user = HospitalApply::GetApplayAll($user_id);
//       halt($user_id);
        if ($user['Status'] == 1) {
            $status = '审核中';
        } elseif ($user['Status'] == 2) {
            $status = '审核完成';
        } elseif ($user['Status'] == 3) {
            $status = '驳回';
        } elseif ($user['Status'] == 4) {
            $status = '审核失败';
        } else
            $status = '';

        View::assign('user_phone', $user_phone);
        View::assign('user_id', $user_id);
//        View::assign('doctor', $doctor);
        View::assign('user', $user);
        View::assign('status', $status);

        return View::fetch('home/grzx_main');
    }

    public function GrzxWdzx()
    {
//        $user_id = Cache::get('users')['id'];
        $user_id = Session::get('users')['id'];
//        halt($user_id);
        $data = UserOrder::GetUserOrderInfo($user_id, StatusCode::USER_ORDER_STATUS_PAID);
//        halt($data->toArray());
        View::assign('data', $data);
        return View::fetch('home/grzx_wdzx');
    }

    public function GrzxJypj()
    {
//        $user_id = Cache::get('users')['id'];
        $user_id = Session::get('users')['id'];
        $data = UserOrder::GetUserOrderInfo($user_id, StatusCode::USER_ORDER_STATUS_PAID);

        View::assign('data', $data);
        return View::fetch('home/grzx_jypj');
    }

    public function GrzxWdgz()
    {
//        $user_id = Cache::get('users')['id'];
        $user_id = Session::get('users')['id'];
        $user_article = Article::GetUserArticle($user_id);

        $user_follow_doctor = Article::GetUserFollowDoctor($user_id);

        View::assign('user_article', $user_article);
        View::assign('user_follow_doctor', $user_follow_doctor);
        return View::fetch('home/grzx_wdgz');
    }

    public function DelFollow($user_id)
    {

        $data = Db::table('userfollow')->where('Doctor', $user_id)->delete();
        if ($data) {
            return $data = ['code' => 200, 'msg' => '已取消'];
        } else {
            return $data = ['code' => 405, 'msg' => '取消失败'];
        }
//        if($data){
//            return "<script>alert('已取消');window.history.go(-1);</script>";
//        }
    }

    public function GrzxJgrz()
    {
//        halt(Cache::get('users')['id']);
        return View::fetch('home/grzx_jgrz');
    }

    public function GrzxYsrz()
    {
//        $user_phone = Cache::get('users')['phone'];
        $user_phone = Session::get('users')['phone'];
        $data = UserService::GetGoodField();
//        halt($data);
        View::assign('data', $data);
        View::assign('user_phone', $user_phone);
        return View::fetch('home/grzx_ysrz');
    }

    public function GrzxXtsz()
    {

        return View::fetch('home/grzx_xtsz');
    }

    public function SaveUserAvatar()
    {
//        $user_id = Cache::get('users')['id'];
        $user_id = Session::get('users')['id'];
        $file = Request::file('user_avatar');
        try {
            $result = validate(['image' => ['fileExt:gif,jpg,png']])->check(['image' => $file]);
//        halt($arr);
//            halt(implode(',', Request::param('specialty')));
            if ($result) {
                $path = Filesystem::disk('public')->putFile('static', $file);
//            dump($path);
                $picCover = Filesystem::getDiskConfig('public', 'url') . '/' . str_replace('\\', '/', $path);

                Db::table('user')->where('ID', $user_id)->update(['UserAvatar' => $picCover]);
                redirect('/home/grzx_main')->send();
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function SjzxMain()
    {
//        $persion = Cache::get('users')['is_persion'];
        $persion = Session::get('users')['is_persion'];
        if ($persion != StatusCode::USER_PERSION) {
            return View::fetch('/home/sjzx_pdtzym');
        }


        return View::fetch('home/sjzx_main');
    }

    public function UpdateHospitalInfo()
    {
        Hospital::where('ID', Request::param('hospital_id'))->save([
            'HospitalInfo' => Request::param('hospital_info')
        ]);
//        halt($data);
        return "<script>alert('修改完成');window.history.back();</script>";
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
        $doctor_id = Request::param('doctor_id');

        View::assign('doctor_id', $doctor_id);
        return View::fetch('home/grzx_zxspj');
    }

    public function UserComment()
    {
        $data = file_get_contents("php://input");
        $result = json_decode($data, true);

        Db::table('evaluate')->insert([
            'DoctorID' => $result['params']['doctor_id'],
            'UserID' => Session::get('users')['id'],
            'Content' => $result['params']['content'],
            'Grade' => $result['grade'],
//            "IsAnonymous" => $result['params']['is_anonymous'],
            "CreateTime" => time()
        ]);

        return redirect('/home/grzx_main')->send();


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
//        halt($info->toArray());
        $info['Specialty'] = json_decode($info['Specialty']);
        $good_field = Db::table('goodfield')->visible(['ID', 'Name'])->select()->toArray();
//        foreach ($info['Specialty'] as $k => $v) {
//            $good_field = Db::table('goodfield')->where('ID', $v)->visible(['ID', 'Name'])->select()->toArray();
////            dump($good_field);
//        }
//        halt($good_field);
        View::assign('info', $info);
        View::assign('good_field', $good_field);

        return View::fetch('home/grzx_ysrztj');

    }

    public function LoginOut()
    {
        Session::delete('users');

        redirect('/home/login')->send();
    }
}