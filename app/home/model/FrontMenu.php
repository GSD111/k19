<?php


namespace app\home\model;


use think\facade\Db;
use think\Model;

class FrontMenu extends Model
{
    protected $table = 'frontmenu';
    protected $readonly = ['id', 'MenuName', 'MenuLocation', 'ParentID'];


    /*
     * 获取头部栏目按钮
     */
    public static function TopMenu()
    {
        $info = FrontMenu::where('MenuLocation', 1)->where('ParentID', 0)->select()->toArray();

        return $info;
    }

    /*
     * 获取栏目按钮下面的子按钮
     * @param string $ID 栏目按钮的id
     */
    public static function GetChildMenu($id)
    {
        $info = FrontMenu::where('ParentID', $id)->select();
//        if ($info->isEmpty()) {
//            redirect('http://home.baidu.com')->send();
//        }
        return $info;
    }
}