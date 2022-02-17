<?php
/**
 *Author: zff
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\Maintenance as MaintenanceModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\MaintenanceException;
use app\api\validate\Maintenance as MaintenanceValidate;
use app\lib\exception\SuccessMessage;

class Maintenance extends BaseController
{
    public function getAllMaintenance() {
        $lists = MaintenanceModel::getMaintenanceLists($page = 1, $size = 30);
        $addressConfig = config('setting.address_config');
        $servicePeriodConfig = config('setting.service_period_config');
        foreach ($lists as $lKey => $lValue) {
            $lists[$lKey]['tool']['address_str'] = $addressConfig[$lValue['tool']['address'] - 1]['label'];
            $lists[$lKey]['tool']['service_period_str'] = $servicePeriodConfig[$lValue['tool']['service_period'] - 1]['label'];
        }

        $result['lists'] = $lists;
        $result['error_code'] = 20000;
        $result['code'] = 200;
        return $result;
    }

    public function getOneMaintenance($id) {
        (new IDMustBePositiveInt())->goCheck();
        $maintenance = MaintenanceModel::getOneMaintenanceByID($id);
        $maintenance['tool']['service_period'] = config('setting.service_period_config')[$maintenance['tool']['service_period'] - 1]['label'];
        $maintenance['tool']['address'] = config('setting.address_config')[$maintenance['tool']['address'] - 1]['label'];

        $result['data'] = $maintenance;
        $result['code'] = 200;
        $result['error_code'] = 20000;
        return $result;
    }

    public function editOneMaintenance($id) {
        (new IDMustBePositiveInt())->goCheck();

        $repair = MaintenanceModel::get($id);
        if (!$repair) {
            throw new MaintenanceException([
                'code' => 404,
                'msg' => '维修单信息错误，请检查参数',
                'errorCode' => 90010
            ]);
        }

        // 校验参数
        $validate = new MaintenanceValidate();
        $validate->goCheck();

        // 2 和model交互，去写具体的流程
        $data = $validate->getDataByRule(input('post.'));
        MaintenanceModel::editMaintenance($id, $data);
        return new SuccessMessage();
    }
}