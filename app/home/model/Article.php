<?php


namespace app\home\model;


use app\home\enum\StatusCode;
use think\Model;

class Article extends Model
{
    protected $table = 'article';

    protected $readonly = ['ID', 'Title', 'Content', 'ArticleImg', 'Status', 'IsRecommend'];

    /*
     * 获取推荐的文章
     */
    public static function RecommendArticle()
    {
        $article = Article::where('Status', StatusCode::ARTICEL_STATUS_SUCCESS)
            ->where('IsRecommend', StatusCode::ARTICEL_ISRECOMMEND)->find();

        return $article;
    }

    /*
     * 根据文章栏目传入的ID值获取对应的文章
     * @param string $id 文章菜单栏目id
     */
    public static function GetByArticle($id)
    {
        $info = Article::where('MenuID', $id)->order('ID desc')->select();
        if ($info->isEmpty()) {

            return $info = Article::where('IsRecommend', StatusCode::ARTICEL_ISRECOMMEND)->select();
        }
        return $info;
    }
}