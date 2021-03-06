<?php


namespace app\home\service;

use app\home\enum\StatusCode;
use app\home\model\FrontMenu as FontMenuModel;
use think\facade\Db;

class FontMenu
{

    /*
     * 检测是否还有子类栏目菜单
     */
    public static function IsChildMenu($id)
    {

        $result = FontMenuModel::GetChildMenu($id)->where('IsDelete',StatusCode::FRONTMENU_ISDELETE);
        if ($result->isEmpty()) {
            redirect('http://kf.k19ch.com/s/554635lj9')->send();
        }

        return $result;
    }

    /*
      * 获取自测中心分类列表信息
      * @param string $id
      */
    public static function GetTypeListInfo($id)
    {
        $data = Db::table('typelist')->where('ParsentID', $id)
            ->order('Grade desc')->select();

        return $data;
    }

}