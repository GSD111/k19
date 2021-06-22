<?php


namespace app\home\model;


use app\home\enum\StatusCode;
use think\facade\Db;
use think\Model;

class AreaCity extends Model
{
    protected $table = 'areacity';

//    public function user(){
//
//        return $this->belongsTo(User::class,'area_id','id');
//    }

    /*
     * 获取所有地区
     */
    public static function GetAreaAll()
    {

        $area = AreaCity::where('ParentsID', 0)->select();
        foreach ($area as $k => $v) {
            $area[$k]['cityall'] = self::GetChildCity($v['ID']);
//            halt($area->toArray());
        }

        return $area;
    }

    /*
     * 获取地区下面的所属城市
     */
    public static function GetChildCity($id)
    {

        return AreaCity::where('ParentsID', $id)->select();
    }

    /*
     * 查询所在城市区域的医师
     */
    public static function GetAreaDoctor($status)
    {

        $all = Db::table('areacity')
            ->join('user', 'user.AreaId = areacity.ID')
            ->where('user.IsDoctor', $status)
            ->visible(['ID', 'UserAvatar', 'CityName', 'RealName', 'PhoneNumber', 'AreaId', 'Remark', 'HospitalID'])
            ->select();
//        halt($all);
        return $all;
    }

    /*
    * 查询所在城市区域的医院
    */
    public static function GetAreaHospital()
    {

        $all = Db::table('areacity')
            ->join('hospital hosp', 'hosp.AreaId = areacity.ID')
            ->join('user u', 'u.HospitalID = hosp.ID')
            ->join('hospitalapply hosapply', 'u.ID = hosapply.UserId')
            ->field('u.ID UID,u.UserAvatar,u.RealName,hosapply.Status,hosapply.Name,hosapply.BusinessTime,
                hosapply.Province pro ,hosapply.City city, hosapply.Area area,hosapply.Address,hosp.*,areacity.*')
            ->where('u.IsPersion', StatusCode::USER_PERSION)
            ->where('hosapply.Status', StatusCode::HOSPITAL_APPLY_SUCCESS)
            ->visible(['UID', 'UserAvatar', 'CityName', 'RealName', 'AreaId', 'Status', 'pro',
                'city', 'area', 'Address', 'BusinessTime','Name'])
            ->select();
//        halt($all);
        return $all;
    }
}