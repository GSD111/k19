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



//前端index页面



Route::group('home',function(){
    Route::get('/index','Index/Index');
    Route::get('/index/:id','Index/Index');
//    Route::get('/index','Index/GetArticle');
    Route::get('/xlzx','Index/Xlzx');
    Route::get('/zczx','Index/Zczx');
    Route::get('/zsxg_list','Index/ZsxgList');
    Route::get('/wzxqs_list','Index/WzxqsList');
    Route::get('/wzxqs_arc','Index/WzxqsArc');
    Route::get('/wzxqs_qs','Index/WzxqsQs');
    Route::get('/xlzxs_arc','Index/XlzxsArc');
    Route::get('/jsjb_list','Index/JsjbList');
    Route::get('/arc_arc','Index/ArcArc');
    Route::get('/gash','Index/Gash');
    Route::get('/gash_zzjg','Index/GashZzjg');
    Route::get('/gash_zx','Index/GashZx');
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

//问医生
    Route::get('/xlzxs_list','Askdoctor/XlzxsList');
    Route::get('/search/:id','Askdoctor/XlzxsList');
    Route::get('/xlzxs_arc','Askdoctor/XlzxsArc');

//找医生
    Route::get('/hosp_list','Findhospital/HospList');
    Route::get('/hosp_search/:id','Findhospital/HospList');
    Route::get('/hosp_arc','Findhospital/HospArc');

//个人中心
    Route::get('/grzx_main','Personalcenter/GrzxMain');
    Route::get('/grzx_wdzx','Personalcenter/GrzxWdzx');
    Route::get('/grzx_jypj','Personalcenter/GrzxJypj');
    Route::get('/grzx_wdgz','Personalcenter/GrzxWdgz');
    Route::get('/grzx_jgrz','Personalcenter/GrzxJgrz');
    Route::get('/grzx_ysrz','Personalcenter/GrzxYsrz');
    Route::get('/grzx_xtsz','Personalcenter/GrzxXtsz');
    Route::get('/sjzx_main','Personalcenter/SjzxMain');
    Route::get('/grzx_csjg','Personalcenter/GrzxCsjg');
    Route::get('/grzx_cspj','Personalcenter/GrzxCspj');
    Route::get('/grzx_zxspj','Personalcenter/GrzxZxspj');
    Route::get('/grzx_jgrztj','Personalcenter/GrzxJgrztj');
    Route::get('/grzx_ysrztj','Personalcenter/GrzxYsrztj');
});
