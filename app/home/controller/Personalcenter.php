<?php


namespace app\home\controller;


use app\BaseController;
use app\home\enum\StatusCode;
use app\home\model\HospitalApply;
use app\home\model\User;
use app\home\service\Article;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Filesystem;
use think\facade\Request;
use think\facade\View;

class Personalcenter extends BaseController
{

    /*
     * 个人中心
     */
    public function GrzxMain()
    {
//        halt(Cache::get('users'));
        $user_phone = Cache::get('users')['phone'];
        $user_id = Cache::get('users')['id'];
        $doctor = Cache::get('users')['doctor'];
        $persion = Cache::get('users')['persion'];
//        halt($persion);
//        /*
//         * 检测当前登录者的身份信息
//         */
        if($persion == StatusCode::USER_PERSION){
            $data = HospitalApply::GetApplayAll($user_id);
            $HospitalDoctor = HospitalApply::GetHospitalDoctor($user_id)->toArray();
//            halt($data);
            View::assign('data',$data);
            View::assign('HospitalDoctor',$HospitalDoctor);
            return View::fetch('home/sjzx_main');
        }


//        $user = HospitalApply::where('ID', $user_id)->find();
        $user = HospitalApply::GetApplayAll($user_id);
//       halt($user);
        if ($user['Status'] == 1) {
            $status = '审核中';
        } elseif ($user['Status'] == 2) {
            $status = '审核完成';
        } elseif ($user['Status'] == 3) {
            $status = '驳回';
        } elseif($user['Status'] == 4) {
            $status = '审核失败';
        }

        View::assign('user_phone', $user_phone);
        View::assign('user_id', $user_id);
        View::assign('doctor', $doctor);
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
        $user_id = Cache::get('users')['id'];
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

        return View::fetch('home/grzx_ysrz');
    }

    public function GrzxXtsz()
    {

        return View::fetch('home/grzx_xtsz');
    }

    public function SaveUserAvatar()
    {
        $user_id = Cache::get('users')['id'];
        $file = Request::file('user_avatar');
        try {
            $result = validate(['image' => ['fileExt:gif,jpg,png']])->check(['image' => $file]);
//        halt($arr);
//            halt(implode(',', Request::param('specialty')));
            if ($result) {
                $path = Filesystem::disk('public')->putFile('static', $file);
//            dump($path);
                $picCover = Filesystem::getDiskConfig('public', 'url') . '/' . str_replace('\\', '/', $path);
//                $user = User::find($user_id);
////                halt($user);
//                $user->UserAvatar = $picCover;
////                halt($user->UserAvatar);
//                $user->save();
                Db::table('user')->where('ID',$user_id)->update(['UserAvatar'=>$picCover]);
                redirect('/home/grzx_main')->send();
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function SjzxMain()
    {
        $persion = Cache::get('users')['persion'];
        if ($persion != StatusCode::USER_PERSION) {
            return View::fetch('/home/sjzx_pdtzym');
        }
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