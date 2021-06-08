<?php


namespace app\home\model;


use think\Model;
use app\home\enum\StatusCode;

class Article extends Model
{
    protected $table = 'article';

    protected $readonly = ['ID', 'Title', 'Content', 'ArticleImg','Status','IsRecommend'];

    public static function RecommendArticle()
    {
        $article = Article::where('Status', StatusCode::ARTICEL_STATUS_SUCCESS)
            ->where('IsRecommend', StatusCode::ARTICEL_ISRECOMMEND)->find();

        return $article;
    }
}