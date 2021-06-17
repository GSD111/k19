<?php


namespace app\home\model;


use think\facade\Db;
use think\Model;

class News extends Model
{
    protected $table = "news";

    protected $visible = ['ID', 'Title', 'NewImg', 'Content'];

    /*
     * 慈善资讯数据列表
     */

    public static function GetNewsList()
    {

        $info = News::limit(10)->order('ID desc')->select()->toArray();

        return $info;
    }

    /*
     * 获取某一条资讯详情
     * @params string $id
     */
    public static function GetByNewId($id)
    {

        $info = News::where('ID', $id)->find();

        return $info;
    }

    /*
     * 慈善资讯详情页相关推荐
     */
    public static function RecommentNews()
    {

        $info = News::limit(2)->orderRaw('rand()')->select();

        return $info;
    }

    /*
     * 慈善机构数据列表
     */
    public static function GetCharityList()
    {
        $info = Db::table('charity')->limit(10)->order('ID desc')->select();

        return $info;
    }

    /*
     * 慈善机构筛选
     * @params string $keywords
     */
    public static function SearchCharity($keywords){

        $info = Db::table('charity')->where('Title', 'like', '%' . $keywords . '%')->select()->toArray();
//        halt($info);
        return $info;
    }
}