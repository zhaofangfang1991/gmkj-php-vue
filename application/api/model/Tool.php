<?php
/**
 *Author: zff
 */

namespace app\api\model;


use app\lib\exception\ToolException;
use app\api\model\Agency as agencyModel;
use app\api\model\Resource as resourceModel;

class Tool extends BaseModel
{
    public function resource()
    {
        return $this->hasMany('Resource', 'belongs_id', 'id')->field('username,type');
    }

    public function chargeAccount() {
        return $this->belongsTo('Account', 'charge_id', 'id');
    }
    public function agency() {
        return $this->belongsTo('Agency', 'agency_id', 'id');
    }

    /**
     * 添加设备流程
     */
    public static function addTool($data) {

        // 1 整理数据
        if ($data['subSort']) {
            $subData['name'] = $data['subName'];
            $subData['no'] = $data['subNo'];
            $subData['sort'] = $data['subSort'];
            $subData['level'] = $data['subLevel'];
            unset($data['subName']);unset($data['subNo']);unset($data['subSort']);unset($data['subLevel']);
        }

        // 2 存当前工具
        $addResult = self::create($data);
        $t_id = $addResult->id;

        // 3 存子工具
        $subData['t_id'] = $t_id;
        $addSubResult = self::create($subData);

        // 4 更新文件
        foreach ($data['pics'] as $key => $value) {
            // TODO 这里应该用数据库的关联关系更新 不会写
            resourceModel::where('url', '=', $value['pic'])->update(['belongs_id' =>  $t_id, 'belongs' => 1]);
        }

        return $t_id;
    }

    public static function getToolLists($page = 1, $size) {
        return self::order('id', 'asc')->paginate($size);
    }
}