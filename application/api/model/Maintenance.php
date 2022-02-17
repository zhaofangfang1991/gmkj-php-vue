<?php
/**
 *Author: zff
 */

namespace app\api\model;
use app\api\service\Token as TokenService;


class Maintenance extends BaseModel
{
    // 与设备的关联模型
    public function tool() {
        return $this->belongsTo('Tool', 'tool_id', 'id')->field('id,name,no,service_period,begin_time,address');
    }

    public function MaintenanceAccount() {
        return $this->belongsTo('Account', 'account_id', 'id')->field('id,username,telnumber');
    }

    /**
     * 通过设备名称和状态搜索
    */
    public static function getMaintenanceLists($page = 1, $size) {
        $params = input();

        $map = array();
        if (array_key_exists('status', $params) and !empty($params['status'])) {
            $map['status'] = $params['status'];
        }
        $tool['name'] = ['like', "%%"];
        if (array_key_exists('tool_name', $params)and !empty($params['tool_name'])) {
            $tool['name'] = ['like', "%" . $params['tool_name'] . "%"];
        }
        if (array_key_exists('limit', $params)) {
            $size = $params['limit'];
        }

        return self::hasWhere('tool',['name'=>$tool['name']])->where($map)->with('tool')->order('status', 'asc')->paginate($size);
    }

    public static function getOneMaintenanceByID($id) {
        return self::with('tool,MaintenanceAccount')->find($id);
    }

    public static function editMaintenance($id, $data) {

        // 1 修改当前保养单的数据
        $uid = TokenService::getCurrentUid();
        $data['account_id'] = $uid;
        $data['status'] = 2;
        self::where('id', '=', $id)->update($data);

        // 2 新增一条下一次的保养单 怎么获取当前这个设备的保养频率？
        $currentMaintenance = self::with('tool')->find($id);
        self::bornNextMaintenance($currentMaintenance['tool']['id'], $currentMaintenance['tool']['service_period']);

        return 1;
    }

    /**
     * 此方法用于生成下一次保养清单
     * 两种情况下会调用：1创建设备时  2填写保养单时
     * @param $id  tool_id                       1
     * @param $service_period  设备对应的保养频率   1
     *
    */
    public static function bornNextMaintenance($id, $service_period) {
        $servicePeriodConfig = config('setting.service_period_config');
        $current_time =  date('Y-m-d H:i:s');
        self::create([
                'tool_id' => $id,
                'current_time' => $current_time,
                'next_time' => date("Y-m-d",(strtotime($current_time) + 3600*24*$servicePeriodConfig[$service_period - 1]['days']))
            ]
        );
    }
}