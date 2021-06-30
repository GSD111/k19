<?php


namespace app\home\enum;


class StatusCode
{
    const USER_ISDOCTOR = 1;           //职业是个人
    const USER_DOCTOR = 2;             //职业是医生
    const USER_ISPERSION = 1;          //入驻是个人
    const USER_PERSION = 2;            //入驻是医院
    const USER_ISSTATUS = 0;           //禁用
    const USER_STATUS = 1;             //启用
    const USER_ISTYPE = 1;             //admin身份
    const USER_TYPE = 2;               //会员身份
    const USER_ISRECOMMEND = 0;        //不推荐
    const USER_RECOMMEND = 1;          //推荐

    const FRONTMENU_PARENTID = 0;      //一级栏目
    const FRONTMENU_ISSHOW = 1;        //前台展示
    const FRONTMENU_SHOW = 2;          //前台不展示
    const FRONTMENU_LOCATIONTOP = 1;   //页面上部
    const FRONTMENU_LOCATIONBOTTOM = 2;//页面底部
    const FRONTMENU_ISDELETE = 1;      //未删除

    const ARTICLE_ISORIGINAL = 1;      //原创
    const ARTICLE_ORIGINAL = 2;        //转载
    const ARTICLE_ISMESSAGE = 1;       //开启留言
    const ARTICLE_MESSAGE = 2;         //关闭留言
    const ARTICLE_STATUS = 1;          //审核中
    const ARTICEL_STATUS_SUCCESS = 2;  //审核通过
    const ARTICEL_STATUS_FAIL = 3;     //审核失败
    const ARTICEL_ISRECOMMEND = 1;     //推荐
    const ARTICEL_RECOMMEND = 2;       //不推荐

    const HOSPITAL_APPLY_STATUS = 1;   //审核中
    const HOSPITAL_APPLY_SUCCESS = 2;  //审核通过
    const HOSPITAL_APPLY_FAIL = 3;     //审核失败
    const HOSPITAL_APPLY_REJECT = 4;   //驳回


    const USER_TEST_FREE = "免费";
    const USER_TEST_PAY= '付费';

    const USER_ORDER_STATUS_UNPAID = 0; //未支付
    const USER_ORDER_STATUS_PAID = 1; //已支付
    const USER_ORDER_STATUS_FAIL = 2; //支付失败
}