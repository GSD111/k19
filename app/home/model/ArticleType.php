<?php


namespace app\home\model;


use think\Model;

class ArticleType extends Model
{
    protected $table = "articletype";

    protected $visible = ['ID', 'Name'];
    protected $type = [
        'CreateTime' => 'timestamp'
    ];

    public static function GetArticleType()
    {

        $data = ArticleType::select();
//        halt($data->toArray());
        return $data;
    }
}