<?php
/**
 *Author: zff
 */

namespace app\api\model;


use app\lib\exception\ToolException;
use app\api\model\Agency as agencyModel;

class Tool extends BaseModel
{
    public function img()
    {
        return $this->hasMany('Resource', 'belongs_id', 'id')->field('username,type');
    }

    public function chargeAccount() {
        return $this->belongsTo('Account', 'charge_id', 'id');
    }
    public function agency() {
        return $this->belongsTo('Agency', 'agency_id', 'id');
    }
    public static function addTool($data) {
        // 1 检查charge_id 负责人ID
        $charge = self::hasWhere('chargeAccount',['id'=>$data['charge_id']])->find();
        if (!$charge) {
            throw new ToolException([
                'code' => 404,
                'msg' => '负责人信息错误，请检查参数',
                'errorCode' => 90002
            ]);
        }

        // 2 检查agency_id 代理商ID 没有则添加
        $agency_id = 0;
        if (!key_exists('agency_id', $data)) {
            // 没有现成的代理商，则添加
            $agencyData['username'] = $data['agency_username'];
            $agencyData['telnumber'] = $data['agency_telnumber'];
            $agencyData['gender'] = $data['agency_gender'];
            $agencyData['manufacturer'] = $data['agency_manufacturer'];
            $agencyData['agent'] = $data['agency_agent'];
            $agencyResult = agencyModel::create($agencyData);
//            $agency_id = agencyModel::getLastInsID();
            $agency_id = $agencyResult->id;
        }
        $agency_id = $agency_id?$agency_id:$data['agency_id'];
        $result = self::create($data);
        return $result;
    }
}