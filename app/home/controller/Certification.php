<?php


namespace app\home\controller;


use app\BaseController;
use think\facade\Request;

class Certification extends BaseController
{
    public function PeopleCertification()
    {
        halt(input('param.'));
    }
}