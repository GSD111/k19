<?php


namespace app\home\model;


use think\Model;

class TypeList extends Model
{
    protected $table = "typelist";


    /*
     * 获取某一个分类的详细信息
     * @params string $type_id  分类的id
     */
    public static function GetTypeInfo($type_id)
    {
        return TypeList::where('ID',$type_id)->find();
    }
}