<?php


namespace app\home\controller;


use app\BaseController;
use app\home\model\HospitalApply;
use app\home\model\User;
use think\facade\Filesystem;
use think\facade\Request;
use think\facade\Session;

class Certification extends BaseController
{

    /*
     * 医师入驻申请
     */
    public function PeopleCertification()
    {

//        $data = HospitalApply::where('UserId', Session::get('users')['id'])->find();
//
//        if(!empty($data)){
//
//            return "<script>alert('您已经认证过了请勿再次提交申请');window.history.back();</script>";
//        }
        $data = HospitalApply::IsUserCertification(Session::get('users')['id']);
        if (!empty($data)) {

            return "<script>alert('您已经认证过了请勿再次提交申请');window.history.back();</script>";
        }

        $is_exist = HospitalApply::where('Name', Request::param('name'))->select();
//        halt($is_exist);
        if (!empty($is_exist)) {
            return "<script>alert('该名称已被认证占用了,如有疑问请联系客服人员进行处理');window.history.back();</script>";
        }

        $file = Request::file('license_permission');
        try {
            $result = validate(['image' => ['fileExt:gif,jpg,png']])->check(['image' => $file]);
//        halt($arr);
//            halt(implode(',', Request::param('specialty')));
            if ($result) {
                $path = Filesystem::disk('public')->putFile('static', $file);
//            dump($path);
                $picCover = Filesystem::getDiskConfig('public', 'url') . '/' . str_replace('\\', '/', $path);

                User::where('ID', Session::get('users')['id'])->save([
                    'RealName' => Request::param('name'),
                    "IsPersion" => Request::param('is_persion'),
                    "Remark" => Request::param('Remark'),
                ]);
//            halt($picCover);
                $user = new HospitalApply;
                $user->Name = Request::param('name');
                $user->Province = Request::param('province');
                $user->City = Request::param('city');
                $user->Area = Request::param('area');
                $user->Address = Request::param('address');
                $user->Specialty = json_encode(Request::param('specialty'));
                $user->UserPhone = Request::param('user_phone');
                $user->UserId = Session::get('users')['id'];
                $user->LicensePermission = $picCover;
//                $user->IsPersion = Request::param('is_persion');
                $user->BusinessTime = Request::param('business_time');
                $user->CreateTime = time();
                $user->save();


                redirect('/home/grzx_ysrztj/' . $user->id)->send();
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    /*
     * 医院入驻申请
     */
    public function HospitalCertification()
    {

        $data = HospitalApply::IsUserCertification(Session::get('users')['id']);
        if (!empty($data)) {

            return "<script>alert('您已经认证过了请勿再次提交申请');window.history.back();</script>";

        }

        $is_exist = HospitalApply::where('Name', Request::param('name'))->select();
        if (!empty($is_exist)) {
            return "<script>alert('该名称已被认证占用了,如有疑问请联系客服人员进行处理');window.history.back();</script>";
        }


//        halt(Request::param());
        $files = Request::file('business_license');
        try {
            $rules = validate(['images' => ['fileExt:gif,jpg,png']])->check(['images' => $files]);
//        halt($arr);
            if ($rules) {
                $paths = Filesystem::disk('public')->putFile('static', $files);
                User::where('ID', Session::get('users')['id'])->save([
                    'RealName' => Request::param('name'),
                    "IsPersion" => Request::param('is_persion')
                ]);
//            dump($path);
                $picCover = Filesystem::getDiskConfig('public', 'url') . '/' . str_replace('\\', '/', $paths);
                $user = new HospitalApply;
                $user->Name = Request::param('name');
                $user->Province = Request::param('province');
                $user->City = Request::param('city');
                $user->Area = Request::param('area');
                $user->Address = Request::param('address');
                $user->UserName = Request::param('user_name');
                $user->UserPhone = Request::param('user_phone');
                $user->UserId = Session::get('users')['id'];
                $user->BusinessLicense = $picCover;
//                $user->IsPersion = Request::param('is_persion');
                $user->BusinessTime = Request::param('business_time');
                $user->CreateTime = time();
                $user->save();

                redirect('/home/grzx_jgrztj/' . $user->id)->send();
            }
        } catch (\Exception $e) {

            return $e->getMessage();
        }

    }
}