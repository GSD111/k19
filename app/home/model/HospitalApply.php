<?php


namespace app\home\model;


use think\facade\Db;
use think\Model;

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
     * 获取的医院的具体信息
     * @params string  $user_id   医院的id
     */
    public static function GetApplyDetail($user_id)
    {

        $HospitalDeatil = Db::table('hospitalapply')
            ->join('user', 'user.ID = hospitalapply.UserId')
            ->where('UserId', $user_id)
            ->visible(['Name', 'Province', 'City', 'Area', 'Address', 'UserPhone', 'BusinessTime', 'UserAvatar', 'Remark'])
            ->find();

        return $HospitalDeatil;

    }


    /*
     * 获取医院里的医师
     * @params string  $user_id   医院的id
     */
    public static function GetHospitalDoctor($user_id)
    {
        $data = User::where('HospitalID', $user_id)->visible(['ID','UserAvatar','RealName'])->select();
        return $data;
    }
}