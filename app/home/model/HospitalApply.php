<?php


namespace app\home\model;


use app\home\enum\StatusCode;
use app\home\service\User as UserService;
use think\facade\Db;
use think\facade\Session;
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


//    /*
//     * 检测当前用户是否认证过
//     * @params string $id 当前用户的id
//     *
//     */
//    public static function IsApply($id)
//    {
//
//        $data = HospitalApply::where('UserId', $id)->find();
////        halt($data);
//        if (!empty($data)) {
//            return "<script>alert('您已经认证过了请勿再次提交申请');window.history.back();</script>";
//        }
//        return $data;
//    }


    /*
     * 获取商家申请入驻者的详细信息
     * @params string $id  用户的id
     */
    public static function GetApplayAll($user_id)
    {
        $ApplayAll = Db::table('hospitalapply')
            ->join('user', 'user.ID = hospitalapply.UserId')
            ->where('UserId', $user_id)
            ->field('user.RealName,user.Remark,user.UserAvatar,user.HospitalID,user.ID UID,hospitalapply.*')
            ->visible(['Name', 'Province', 'City', 'Area', 'Address', 'UID',
                'UserPhone', 'BusinessTime', 'UserAvatar', 'Remark',
                'HospitalID', 'UserId', 'RealName', 'Specialty', 'UserName', 'Status'])
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
        $HospitalDeatil = UserService::GetHospitalAll()->where('UID', $user_id)->first();

        return $HospitalDeatil;

    }


    /*
     * 获取个人入驻医师的具体信息
     * @params string  $user_id   用户的id
     */

    public static function GetDoctorDetail($user_id)
    {

        $info = self::GetApplayAll($user_id);

//        $info = Db::table('hospitalapply')
//            ->join('user u', 'u.ID = hospitalapply.UserId')
//            ->where('UserId', $user_id)
//            ->field('u.ID UID ,u.RealName,u.UserAvatar,u.Remark,u.HospitalID,hospitalapply.*')
//            ->visible(['UID', 'Name', 'Province', 'City', 'Area', 'Address',
//                'UserPhone', 'BusinessTime', 'UserAvatar', 'Remark', 'HospitalID',
//                'HospitalID', 'UserId', 'RealName', 'Specialty', 'UserName', 'Status'])
//            ->find();

        return $info;
    }

    /*
     * 获取医院里的医师及留言信息
     * @params string  $hospital_id   医院id
     */
    public static function GetHospitalDoctor($hospital_id)
    {
//        $data = User::where('HospitalID', $hospital_id)
//            ->where('IsDoctor', StatusCode::USER_DOCTOR)
//            ->visible(['ID', 'UserAvatar', 'RealName', 'Remark'])->select()->toArray();
        $data = DB::table('user')
            ->join('hospitalapply hosapply', 'user.ID = hosapply.UserId')
            ->field('user.RealName,user.HospitalID,user.UserAvatar,user.ID UID,user.Remark,user.IsDoctor,hosapply.*')
            ->where('HospitalID', $hospital_id)
            ->where('hosapply.Status', StatusCode::HOSPITAL_APPLY_SUCCESS)
            ->where('user.IsDoctor', StatusCode::USER_DOCTOR)
            ->select()->toArray();

        foreach ($data as $k => $v) {
            $data[$k]['article_message'] = self::GetArticleMessage($v['ID']);
//            halt($data);
        }
//       halt($data);
        return $data;
    }


    /*
     * 获取用户的留言信息
     *  @params string  $user_id   用户的id
     */
    public static function GetArticleMessage($user_id)
    {
        $data = Db::table('articlemessage')
            ->join('user', 'user.ID = UserID')
            ->join('article', 'article.ID = ArticleID ')
            ->join('articletype', 'articletype.ID = article.ArticleType ')
            ->field('user.RealName,article.Title,article.ArticleType,articletype.Name,articlemessage.*')
            ->where('articlemessage.UserID', $user_id)
            ->visible(['ID', 'UserID', 'ArticleID', 'MessageContent', 'CreateTime', 'RealName', 'Title', 'Name', 'ArticleType', 'IsAnonymous'])
            ->order('CreateTime desc')
            ->limit(15)
            ->select()->toArray();

        return $data;
    }


    /*
     * 检测当前用户是否已进行认证
     * @params string $user_id  //用户的id
     */
    public static function IsUserCertification($user_id)
    {
        $data = HospitalApply::where('UserId',$user_id)->find();

        return $data;
    }
}