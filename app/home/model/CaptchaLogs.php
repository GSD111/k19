<?php


namespace app\home\model;


use think\Model;

class CaptchaLogs extends Model
{
    protected $table = 'captchalogs';

//

    protected $type = [
        'CreateTime' => 'timestamp',
    ];
}