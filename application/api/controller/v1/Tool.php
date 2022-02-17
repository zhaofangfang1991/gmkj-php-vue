<?php
/**
 *Author: zff
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\ToolNew;
use app\api\model\Tool as ToolModel;
use app\lib\exception\SuccessMessage;
use app\lib\exception\ToolException;
use app\api\model\Review as ReviewModel;

class Tool extends BaseController
{
    /**
    * 设备增
     */
    public function createTool() {
        // 1 校验参数
        $validate = new ToolNew();
        $validate->goCheck();

        // 2 和model交互，去写具体的流程
        $data = $validate->getDataByRule(input('post.'));
        $result = ToolModel::addTool($data);

        // 3 判断model执行的结果
        if (!$result) {
            throw new ToolException();
        }

        // 4 返回json
        return new SuccessMessage();
    }

    public function getAllTool($page=1, $limit=20, $name='') {
        $lists = ToolModel::getToolLists($page, $limit, $name);
        $addressConfig = config('setting.address_config');
        $servicePeriodConfig = config('setting.service_period_config');
        foreach ($lists as $lKey => $lValue) {
            $lists[$lKey]['address'] = $addressConfig[$lValue['address'] - 1]['label'];
            $lists[$lKey]['service_period'] = $servicePeriodConfig[$lValue['service_period'] - 1]['label'];
        }

        $result['error_code'] = 20000;
        $result['lists'] = $lists;
        return $result;
    }

    // 返回保养频率配置项
    public function getServicePeriod() {
        $result['data'] = config('setting.service_period_config');
        $result['code'] = 200;
        $result['error_code'] = 20000;
        return $result;
    }
    // 返回车间配置项
    public function getAddressConfig() {
        $result['data'] = config('setting.address_config');
        $result['code'] = 200;
        $result['error_code'] = 20000;
        return $result;
    }

    public function deleteOne($id) {
        (new IDMustBePositiveInt())->goCheck();
        ToolModel::destroy($id);
        // 删除相关的巡检数据
        ReviewModel::destroy(['t_id' => $id]);
        return new SuccessMessage();
    }

    public function getOneTool($id) {
        (new IDMustBePositiveInt())->goCheck();
        $tool = ToolModel::getOneToolByID($id);
        if ($tool['sub_tool'] != array()) {
            $tool['subName'] = $tool['sub_tool'][0]['name'];
            $tool['subNo'] = $tool['sub_tool'][0]['no'];
            $tool['subSort'] = $tool['sub_tool'][0]['sort'];
            unset($tool['sub_tool']);
        }

        $result['data'] = $tool;
        $result['data']['pics'] = array();
        $result['code'] = 200;
        $result['error_code'] = 20000;
        return $result;
    }

    public function editTool($id) {
        (new IDMustBePositiveInt())->goCheck();

        $tool = ToolModel::get($id);
        if (!$tool) {
            throw new ToolException([
                'code' => 404,
                'msg' => '设备不存在, 请检查参数',
                'errorCode' => 90004
            ]);
        }

        // 校验参数
        $validate = new ToolNew();
        $validate->goCheck();

        // 2 和model交互，去写具体的流程
        $data = $validate->getDataByRule(input('post.'));
        ToolModel::editTool($id, $data);
        return new SuccessMessage();
    }

    /**
     * 维修单的设备列表
    */
    public function getToolListsForRepair($name='', $type=2) {
        $lists = ToolModel::getToolLists($page=1, $limit=1,$name, $type=2);
        $result['error_code'] = 20000;
        $result['lists'] = $lists;
        return $result;
    }
}