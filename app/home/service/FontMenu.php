<?php


namespace app\home\service;

use app\home\model\FrontMenu as FontMenuModel;

class FontMenu
{
    public static function IsChildMenu($id)
    {

        $result = FontMenuModel::GetChildMenu($id);
        if ($result->isEmpty()) {
            redirect('http://home.baidu.com')->send();
        }

        return $result;
    }
}