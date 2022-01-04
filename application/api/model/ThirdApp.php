<?php

namespace app\api\model;

use think\Model;

class ThirdApp extends BaseModel
{
    public static function check($ac, $se)
    {
        $app = self::where('app_id','=',$ac)
            ->where('password_hash', '=',$se)
            ->find();
        return $app;

    }
}
