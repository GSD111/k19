<?php


namespace app\home\model;


use app\home\enum\StatusCode;
use think\facade\Db;
use think\Model;
use app\home\service\User as UserService;

class HospitalApply extends Model
{
    protected $table = 'hospitalapply';
//    protected $hidden = ['Specialty','RejectRemark'];
    protected $type = [
        'CreateTime' => 'timestamp'
    ];

    /*
     * 获取提交申请入驻的详细信息
     * @params string $id  提交申请后的自增id
     */
    public static function GetApplyInfo($id)
    {

        return HospitalApply::where('ID', $id)->find();
    }


    /*
     * 获取商家申请入驻者的详细信息
     * @params string $id  用户的id
     */
    public static function GetApplayAll($user_id)
    {
        $ApplayAll = Db::table('hospitalapply')
            ->join('user', 'user.ID = hospitalapply.UserId')
            ->where('UserId', $user_id)
            ->visible(['Name', 'Province', 'City', 'Area', 'Address',
                'UserPhone', 'BusinessTime', 'UserAvatar', 'Remark',
                'HospitalID', 'UserId', 'RealName', 'Specialty','UserName','Status'])
            ->find();
//        halt($ApplayAll);
        return $ApplayAll;
    }

    /*
     * 获取的医院的具体信息
     * @params string  $user_id   医院的id
     */
    public static function GetHospitalDetail($user_id)
    {

//        $HospitalDeatil = self::GetApplayAll($user_id);
        $HospitalDeatil = UserService::GetHospitalAll()->where('UID',$user_id)->first();

        return $HospitalDeatil;

    }


    /*
     * 获取个人医师的具体信息
     * @params string  $user_id   用户的id
     */

    public static function GetDoctorDetail($user_id)
    {

        $info = Db::table('hospitalapply')
            ->join('user u','u.ID = hospitalapply.UserId')
            ->where('UserId',$user_id)
            ->field('u.ID UID ,u.RealName,u.UserAvatar,u.Remark,u.HospitalID,hospitalapply.*')
            ->visible(['UID','Name', 'Province', 'City', 'Area', 'Address',
                'UserPhone', 'BusinessTime', 'UserAvatar', 'Remark','HospitalID',
                'HospitalID', 'UserId', 'RealName', 'Specialty','UserName','Status'])
            ->find();

        return $info;
    }

    /*
     * 获取医院里的医师
     * @params string  $user_id   用户的医院id
     */
    public static function GetHospitalDoctor($hospital_id)
    {
        $data = User::where('HospitalID', $hospital_id)
            ->where('IsDoctor',StatusCode::USER_DOCTOR)
            ->visible(['ID', 'UserAvatar', 'RealName','Remark'])->select();
//        halt($data);
        return $data;
    }
}