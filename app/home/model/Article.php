<?php


namespace app\home\model;


use app\home\enum\StatusCode;
use think\facade\Db;
use think\Model;

class Article extends Model
{
    protected $table = 'article';

    protected $readonly = ['ID', 'Title', 'Content', 'ArticleImg', 'Status', 'IsRecommend','ReadNum','CreateTimes','ArticleType','UserID'];

    /*
     * 获取推荐的一片文章文章
     */
    public static function RecommendArticle()
    {
        $article = Article::where('Status', StatusCode::ARTICEL_STATUS_SUCCESS)
            ->where('IsRecommend', StatusCode::ARTICEL_ISRECOMMEND)->orderRaw('rand()')->find();

        return $article;
    }

    /*
     * 根据文章栏目传入的ID值获取对应的文章
     * @param string $id 文章菜单栏目id
     */
    public static function GetByArticle($id)
    {
        $info = Article::where('MenuID', $id)->order('ID desc')->limit(15)->select();
        if ($info->isEmpty()) {
            return false;
//            return $info = Article::where('IsRecommend', StatusCode::ARTICEL_ISRECOMMEND)->select();
        }
        return $info;
    }


    /*
     * 获取所有审核通过的文章
     */
    public static function GetArticleAll(){
        $info = Db::table('article')
            ->join('user','user.ID = article.UserID')
            ->where('Status',StatusCode::ARTICEL_STATUS_SUCCESS)
            ->visible(['ID','Title','Content','ReadNum','CreateTimes','Status','RealName','ArticleType','ArticleImg'])
            ->select();
//        halt($info);
        return $info;
    }


    /*
     * 根据文章的分类进行筛选
     * @params string $id  文章分类id
     */
    public static function SearchArticle($id){

        $info = self::GetArticleAll()->where('ArticleType',$id);

        return $info;
    }


    /*
     * 根据文章关键字筛选文章
     * @params string $keywords
     */

    public static function SearchKeywords($keywords){

        $info = self::GetArticleAll()->whereLike('Title',$keywords);

        return $info;
    }


}