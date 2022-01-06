<?php
/**
 *Author: zff
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\Agency as agencyModel;

class Agency extends BaseController
{
    public function getAllAgency() {
        $result['data'] = agencyModel::field('id,username,telnumber,gender,manufacturer,agent')->select();
        $result['code'] = 200;
        $result['error_code'] = 20000;
        return $result;
    }
}

