<?php


namespace app\home\controller;


use app\BaseController;
use app\home\model\HospitalApply;
use think\facade\Cache;
use think\facade\Filesystem;
use think\facade\Request;

class Certification extends BaseController
{

    /*
     * 医师入驻申请
     */
    public function PeopleCertification()
    {
        $file = Request::file('license_permission');
        try {
            $result = validate(['image' => ['fileExt:gif,jpg,png']])->check(['image' => $file]);
//        halt($arr);
//            halt(implode(',', Request::param('specialty')));
            if ($result) {
                $path = Filesystem::disk('public')->putFile('static', $file);
//            dump($path);
                $picCover = Filesystem::getDiskConfig('public', 'url') . '/' . str_replace('\\', '/', $path);
//            halt($picCover);
                $user = new HospitalApply;
                $user->Name = Request::param('name');
                $user->Province = Request::param('province');
                $user->City = Request::param('city');
                $user->Area = Request::param('area');
                $user->Address = Request::param('address');
                $user->Specialty = json_encode(Request::param('specialty'));
                $user->UserPhone = Request::param('user_phone');
                $user->UserId = Cache::get('users')['id'];
                $user->LicensePermission = $picCover;
                $user->BusinessTime = Request::param('business_time');
                $user->CreateTime = time();
                $user->save();

//            dd(json(Request::param('specialty')));
//            $user = HospitalApply::create([
//                'Name' => Request::param('name'),
//                'Province' => Request::param('province'),
//                'City' => Request::param('city'),
//                'Area' => Request::param('area'),
//                'Address' => Request::param('address'),
//                'Specialty' => Request::param('specialty'),
//                'UserPhone' => Request::param('user_phone'),
//                'LicensePermission' => $picCover,
//                'BusinessTime' => Request::param('business_time'),
//                'CreateTime' => time()
//            ]);


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
        $files = Request::file('business_license');
        try {
            $rules = validate(['images' => ['fileExt:gif,jpg,png']])->check(['images' => $files]);
//        halt($arr);
            if ($rules) {
                $paths = Filesystem::disk('public')->putFile('static', $files);
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
                $user->UserId = Cache::get('users')['id'];
                $user->BusinessLicense = $picCover;
                $user->BusinessTime = Request::param('business_time');
                $user->CreateTime = time();
                $user->save();

                redirect('/home/grzx_jgrztj/' . $user->id);
            }
        } catch (\Exception $e) {

            return $e->getMessage();
        }

    }
}