<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;



Route::group('home',function(){
    Route::get('/index','Index/Index');
    Route::get('/index/:id','Index/Index');
//    Route::get('/index','Index/GetArticle');
    Route::get('/xlzx','Index/Xlzx');
    Route::get('/zczx','Index/Zczx');
    Route::get('/zsxg_list','Index/ZsxgList');
    Route::get('/TypeSearch/:type_id','Index/ZsxgList');
    Route::get('/SearchKeywords','Index/ZsxgList');
    Route::get('/zsxg_list2','Index/ZsxgList2');
    Route::get('/wzxqs_list','Index/WzxqsList');
    Route::get('/wzxqs_arc/:id','Index/WzxqsArc');
    Route::get('/wzxqs_qs/:id','Index/WzxqsQs');
    Route::post('/UserTalkOrder','Index/GetUserTalkOrder');


    Route::get('/jsjb_list','Index/JsjbList');

    Route::get('/xlzx_dh','Index/ZlzxDh');
    Route::get('/xlzx_qt','Index/XlzxQt');
    Route::get('/xlcs_list','Index/XlcsList');
    Route::get('/xlcs_arc','Index/XlcsArc');
    Route::get('/ylcs01','Index/Ylcs01');
    Route::get('/ylcs02','Index/Ylcs02');
    Route::get('/ylcs03','Index/Ylcs03');
    Route::get('/ylcs04','Index/Ylcs04');

    //首页登录
    Route::any('/login','Homelogin/login');
    Route::post('/SendCaptcha','Homelogin/SendCaptcha');
    Route::post('/SingIn','Homelogin/SingIn');

    //关爱社会
    Route::get('/gash','CaringSociety/Gash');
    Route::get('/gash_zzjg','CaringSociety/GashZzjg');
    Route::get('/gash_zx/:id','CaringSociety/GashZx');

    //问医生
    Route::get('/xlzxs_list','Askdoctor/XlzxsList');
    Route::get('/search/:id','Askdoctor/XlzxsList');


    //找医院
    Route::get('/hosp_list','Findhospital/HospList');
    Route::get('/hosp_search/:id','Findhospital/HospList');


    //个人中心
    Route::get('/grzx_main','Personalcenter/GrzxMain');



    Route::get('/login_out','Personalcenter/LoginOut');

//    Route::get('/hosp_arc/:user_id','Findhospital/HospArc');
//    Route::get('/xlzxs_arc/:user_id','Askdoctor/XlzxsArc');
});

/*
 * 中间件验证
 */
Route::group('home',function (){
    Route::get('/arc_arc/:id','Index/ArcArc');
    Route::get('/article_read_num/:id','Index/ArcReadNum');
    Route::post('user_message','Index/userMessage');
    Route::get('/hosp_arc/:user_id','Findhospital/HospArc');
    Route::get('/xlzxs_arc/:user_id','Askdoctor/XlzxsArc');
    Route::post('/UserFollow/:user_id','Askdoctor/GetFollow');
    Route::get('/UnFollow/:user_id','Personalcenter/DelFollow');
    //个人认证
    Route::any('/people_certification','Certification/PeopleCertification');
    Route::any('/hospital_certification','Certification/HospitalCertification');

//    Route::get('/arc_arc/:id','Index/ArcArc');
    Route::get('/grzx_ysrz','Personalcenter/GrzxYsrz');

    Route::get('/grzx_ysrztj/:id','Personalcenter/GrzxYsrztj');
    Route::get('/grzx_xtsz','Personalcenter/GrzxXtsz');
    Route::post('/user_avatar','Personalcenter/SaveUserAvatar');
//    Route::get('/arc_arc','Index/ArcArc');
//    Route::get('/xlzxs_arc','Askdoctor/XlzxsArc');
//    Route::get('/hosp_arc','Findhospital/HospArc');
    Route::get('/grzx_wdzx','Personalcenter/GrzxWdzx');
    Route::get('/grzx_jypj','Personalcenter/GrzxJypj');
    Route::get('/grzx_wdgz','Personalcenter/GrzxWdgz');
    Route::get('/grzx_jgrz','Personalcenter/GrzxJgrz');
//    Route::get('/grzx_ysrz','Personalcenter/GrzxYsrz');
//    Route::get('/grzx_xtsz','Personalcenter/GrzxXtsz');
    Route::get('/sjzx_main','Personalcenter/SjzxMain');
    Route::get('/grzx_csjg','Personalcenter/GrzxCsjg');
    Route::get('/grzx_cspj','Personalcenter/GrzxCspj');
    Route::get('/grzx_zxspj','Personalcenter/GrzxZxspj');
    Route::get('/grzx_jgrztj/:id','Personalcenter/GrzxJgrztj');
    Route::get('/grzx_ysrztj/:id','Personalcenter/GrzxYsrztj');
})->middleware(\app\middleware\IsLogin::class);
