<?php


namespace app\home\model;


use app\home\enum\StatusCode;
use think\facade\Db;
use think\Model;

class AreaCity extends Model
{
    protected $table = 'areacity';

    public function user(){

        return $this->belongsTo(User::class,'area_id','id');
    }

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
     * 是否是热门城市
     */
    public static function IsHotCity()
    {
        return AreaCity::where('ParentsID', '<>', 0)
            ->where('IsHot', 1)->limit(7)->select();
    }

    /*
     * 查询所在城市区域的医师和医院
     */
    public static function GetAreaDoctorOrHospital()
    {
        $all = self::with('user')->select()->toArray();
//        $all = Db::table('areacity')
//            ->join('user', 'user.AreaId = areacity.ID')
//            ->where('user.IsDoctor',StatusCode::USER_DOCTOR)
//            ->visible(['ID','UserAvatar','CityName', 'RealName', 'PhoneNumber','AreaId'])
//            ->select();
        halt($all);
//        return $all;
    }
}