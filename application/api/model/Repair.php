<?php
/**
 *Author: zff
 */

namespace app\api\model;

use app\api\service\Token as TokenService;
use app\api\model\Account as AccountModel;


class Repair extends BaseModel
{
    // 与设备的关联模型
    public function tool() {
        return $this->belongsTo('Tool', 'tool_id', 'id')->field('id,name,no');
    }

    // 与account表的关联模型-操作人
    public function chargeAccount() {
        return $this->belongsTo('Account', 'account_id', 'id')->field('id,username,telnumber');
    }

    // 与account表的关联模型-维修人员
    public function repairAccount() {
        return $this->belongsTo('Account', 'repair_id', 'id')->field('id,username,telnumber');
    }
    // 与account表的关联模型-维修人员
    public function realRepair() {
        return $this->belongsTo('Account', 'real_repair', 'id')->field('id,username,telnumber');
    }
    /**
     * 新建维修单
     * 整理数据 存数据库
    */
    public static function addRepair($data) {
        // 根据token值获取当前登录者的id
        $data['account_id'] = TokenService::getCurrentUid();
        $data['status'] = 1;

        return self::create($data);
    }

    /**
     * 列表查询
     * params: tool_name  status
     * 如果是管理员权限用户查看，则查看所有的数据
     * 如果是仅仅维修员权限查看，则只能查看分配给他的维修数据
     */
    public static function getRepairLists($page = 1, $size) {
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

        // 根据登录者的ID来区分 1前登录者是管理员，显示所有维修列表 2当前登录者是维修人员，仅显示当前维修人员的维修单
        $current_uid = TokenService::getCurrentUid();
        $current_type = AccountModel::where('id', '=', $current_uid)->value('type');

        if ($current_type == 2) {
            $map['repair_id'] = $current_uid;
        }

        return self::hasWhere('tool',['name'=>$tool['name']])->where($map)->with('tool,chargeAccount,repairAccount,realRepair')->order('status', 'asc')->paginate($size);
//        echo self::getLastSql();
    }

    public static function getOneRepairByID($id) {
        return self::with('tool,chargeAccount,repairAccount,realRepair')->find($id);
    }

    public static function editRepair($id, $data) {

        // 通过token获取当前登录用户的id--实际操作人
        $uid = TokenService::getCurrentUid();
        $data['real_repair'] = $uid;
        $data['repair_time'] = date('Y-m-d H:i:s');

        self::where('id', '=', $id)->update($data);

        return 0;
    }
}