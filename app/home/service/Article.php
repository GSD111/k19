<?php


namespace app\home\service;

use think\facade\Db;

class Article
{

    /*
     * 获取文章的相信信息
     * @params string $id 文章的id
     */
    public static function GetArticleDetail($id)
    {
        $article = Db::table('article')->join('user', 'user.ID = article.UserID')->where('article.ID', $id)
            ->visible(['ID', 'Title', 'RealName', 'Content', 'ReadNum', 'CreateTimes', 'ArticleImg','IsMessage'])->find();
//        Db::table('article')->where('ID',$id)->inc('ReadNum');

        return $article;
    }

    /*
     * 更新文章的阅读次数
     * @params string $article_id
     */
    public static function ArticleReadNum($article_id){
        return Db::table('article')->where('ID',$article_id)->inc('ReadNum')->update();
    }

    /*
     * 获取文章的用户评论
     * @params string $article_id
     */
    public static function GetUserArticleMessage($article_id){
        $data = Db::table('articlemessage')
            ->join('user','user.ID = articlemessage.UserID')
            ->where('articlemessage.ArticleID',$article_id)
            ->visible(['RealName','MessageContent','ParsentID','CreateTimes'])
            ->select();

        return $data;
    }

    /*
     * 将用户浏览的文章进行记录
     * @params string $user_id,$article
     */
    public static function UserArticleRecord($user_id, $article_id)
    {
        if(!empty($user_id)){
            $result = Db::table('userarticle')
                ->where('ArticleID', $article_id)
                ->where('UserID', $user_id)
                ->find();
            if (empty($result)) {
                Db::table('userarticle')->save([
                    'UserID' => $user_id,
                    'ArticleID' => $article_id,
                    'CreateTime' => time()
                ]);
            } else {
                Db::table('userarticle')->where('ArticleID', $article_id)->save([
                    'UpdateTime' => time(),
                ]);

            }
        }


    }

    /*
     * 用户浏览文章的记录查询
     * prams string $user_id
     */

    public static function GetUserArticle($user_id)
    {

        $data = Db::table('userarticle')
            ->join('article', 'article.ID  = userarticle.ArticleID')
            ->where('userarticle.UserID', $user_id)
            ->visible(['ID', 'Title', 'CreateTime', 'ReadNum', 'Content'])
            ->select()->toArray();

        return $data;
    }


    /*
     * 用户关注的医师
     * @params user_id string $user_id
     */

    public static function GetUserFollowDoctor($user_id){

        $data = Db::table('userfollow')->join('user','user.ID = userfollow.Doctor')
            ->where('userfollow.UserID',$user_id)
            ->visible(['ID','RealName','UserAvatar'])
            ->select()->toArray();
//       halt($data);
        return $data;

    }
}