<?php


namespace app\home\service;

use think\facade\Db;

class Article
{
    public static function GetArticleDetail($id)
    {
        $article = Db::table('article')->join('user', 'user.ID = article.UserID')->where('article.ID', $id)
            ->visible(['ID','Title','RealName','Content','ReadNum','CreateTimes'])->find();

        return $article;
    }
}