<?php
/**
 * Author: zff
 * Date: 2017/3/19
 * Time: 3:00
 */

namespace app\api\behavior;


use think\Response;

class CORS
{
    public function appInit(&$params)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: token,Origin, X-Requested-With, Content-Type, Accept, Authentication");
        header('Access-Control-Allow-Methods: POST,GET,DELETE');
        if(request()->isOptions()){
            exit();
        }
    }
}