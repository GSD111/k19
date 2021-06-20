<?php


namespace app\home\controller;


use app\BaseController;
use app\home\enum\StatusCode;
use app\home\model\AreaCity as AreaCityModel;
use app\home\model\HospitalApply;
use app\home\service\User as UserService;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Askdoctor extends BaseController
{

    /*
     * 问医生
     */
    public function XlzxsList()
    {

//        halt(Cache::get('users'));
        $searchID = Request::param('id');
        $keywords = Request::param('city');

        if (!empty($searchID)) {
            $data = UserService::SearchAreaDoctor($searchID);
        } elseif (!empty($keywords)) {
            $data = AreaCityModel::GetAreaDoctor(StatusCode::USER_DOCTOR)->whereLike("CityName", $keywords);

        } else {
            $data = UserService::GetDoctorAll();
//            halt($data);
        }


        $area = AreaCityModel::GetAreaAll()->toArray();
//        halt($area);
//        $hotcity = AreaCityModel::IsHotCity()->toArray();

        View::assign('keywords', $keywords);
        View::assign('area', $area);
//        View::assign('hotcity',$hotcity);
        View::assign('data', $data);
        return View::fetch('home/xlzxs_list');

    }


    /*
     * 医生详情页
     * @parmas string $user_id  医生的id
     */
    public function XlzxsArc($user_id)
    {

        $DoctorDetail = HospitalApply::GetDoctorDetail($user_id);
//        halt($DoctorDetail);
        $DoctorDetail['Specialty'] = json_decode($DoctorDetail['Specialty']);
        $ParentName = HospitalApply::where('UserId', $DoctorDetail['HospitalID'])->visible(['Name', 'UserId'])->find();
        if ($DoctorDetail['HospitalID'] == 0) {
            $ParentName = '';
        }

        $result = Db::table('userfollow')->where('UserID',Cache::get('users')['id'])
            ->where('Doctor',$user_id)
            ->find();
//        halt($result);

//        halt($DoctorDetail);
        View::assign('result',$result);
        View::assign('DoctorDetail', $DoctorDetail);
        View::assign('ParentName', $ParentName);

        return View::fetch('home/xlzxs_arc');
    }


    public function GetFollow($user_id)
    {

        $UserId = Cache::get('users')['id'];
        $info = UserService::UserFollowDoctor($UserId,$user_id);
        if ($info == true) {

            return $data = ['code' => 200, 'msg' => '关注成功'];
        } else {
            return $data = ['code' => 405, 'msg' => '关注失败'];
        }
    }

}