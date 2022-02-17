<?php
/**
 *Author: zff
 */

namespace app\api\controller\v1;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\Repair as RepairValidate;
use app\api\validate\RepairEdit as RepairEditValidate;


use app\api\controller\BaseController;
use app\api\model\Repair as RepairModel;
use app\lib\exception\RepairException;
use app\lib\exception\SuccessMessage;

class Repair extends BaseController
{
    /**
     * 创建维修单
    */
    public function createRepair() {
        // 1 校验参数
        $validate = new RepairValidate();
        $validate->goCheck();

        // 2 和model交互，去写具体的流程
        $data = $validate->getDataByRule(input('post.'));
        $result = RepairModel::addRepair($data);

        // 3 判断model执行的结果
        if (!$result) {
            throw new RepairException();
        }

        return new SuccessMessage();
    }


    public function getAllRepair() {
        $lists = RepairModel::getRepairLists($page = 1, $size = 30);

        $result['error_code'] = 20000;
        $result['lists'] = $lists;
        return $result;
    }

    public function getOneRepair($id) {
        (new IDMustBePositiveInt())->goCheck();
        $repair = RepairModel::getOneRepairByID($id);

        $result['data'] = $repair;
        $result['code'] = 200;
        $result['error_code'] = 20000;
        return $result;
    }

    public function editOneRepair($id) {
        (new IDMustBePositiveInt())->goCheck();

        $repair = RepairModel::get($id);
        if (!$repair) {
            throw new RepairException([
                'code' => 404,
                'msg' => '维修单信息错误，请检查参数',
                'errorCode' => 90009
            ]);
        }

        // 校验参数
        $validate = new RepairEditValidate();
        $validate->goCheck();

        // 2 和model交互，去写具体的流程
        $data = $validate->getDataByRule(input('post.'));
        RepairModel::editRepair($id, $data);
        return new SuccessMessage();
    }
}