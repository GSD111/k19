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
//     * 是否是热门城市
//     */
//    public static function IsHotCity()
//    {
//        return AreaCity::where('ParentsID', '<>', 0)
//            ->where('IsHot', 1)->limit(7)->select();
//    }

    /*
     * 查询所在城市区域的医师
     */
    public static function GetAreaDoctor($status)
    {

        $all = Db::table('areacity')
            ->join('user', 'user.AreaId = areacity.ID')
            ->where('user.IsDoctor',$status)
            ->visible(['ID','UserAvatar','CityName', 'RealName', 'PhoneNumber','AreaId','Remark','HospitalID'])
            ->select();
//        halt($all);
        return $all;
    }

    /*
    * 查询所在城市区域的医院
    */
    public static function GetAreaHospital($status)
    {

        $all = Db::table('areacity')
            ->join('user', 'user.AreaId = areacity.ID')
            ->join('hospitalapply','user.ID = hospitalapply.UserId')
            ->where('user.IsPersion',$status)
            ->where('Status',StatusCode::HOSPITAL_APPLY_SUCCESS)
            ->visible(['ID','UserAvatar','CityName',
                'Name','AreaId','Status','Province',
                'City','Area','Address','BusinessTime','UserId'])
            ->select();
//        halt($all);
        return $all;
    }
}