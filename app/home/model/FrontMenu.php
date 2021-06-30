<?php


namespace app\home\model;


use app\home\enum\StatusCode;
use app\home\model\Article as ArticleModel;
use think\Model;

class FrontMenu extends Model
{
    protected $table = 'frontmenu';
    protected $readonly = ['ID', 'MenuName', 'MenuLocation', 'ParentID'];


    /*
     * 获取首页头部栏目按钮
     */
    public static function TopMenu()
    {
        $topmenu = FrontMenu::where('MenuLocation', StatusCode::FRONTMENU_LOCATIONTOP)
            ->where('ParentID', StatusCode::FRONTMENU_PARENTID)
            ->where('IsDelete',StatusCode::FRONTMENU_ISDELETE)
            ->select()->toArray();
//        halt($topmenu);
        return $topmenu;
    }

    /*
     * 获取首页底部文章栏目按钮
     */
    public static function BottomMenu()
    {
        $bottommenu = FrontMenu::where('MenuLocation', StatusCode::FRONTMENU_LOCATIONBOTTOM)
            ->where('ParentID', StatusCode::FRONTMENU_PARENTID)
            ->where('IsDelete',StatusCode::FRONTMENU_ISDELETE)
            ->select();
        if ($bottommenu->isEmpty()) {
            return false;
        }
        foreach ($bottommenu as $k => $v) {
            $bottommenu[$k]['article'] = ArticleModel::GetByArticle($v['ID']);
//             dump($v['ID']);
//             dd(ArticleModel::GetByArticle($v['ID']));
        }
//        dump($bottommenu->toArray());
        return $bottommenu;
    }

    /*
     * 获取栏目按钮下面的子按钮
     * @param string $ID 栏目按钮的id
     */
    public static function GetChildMenu($id)
    {
        $info = FrontMenu::where('ParentID', $id)->select();
        return $info;
    }
}