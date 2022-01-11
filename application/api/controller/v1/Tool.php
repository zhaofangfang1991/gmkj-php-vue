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

    public function getAllTool($page, $limit, $name='') {
        $lists = ToolModel::getToolLists($page, $limit, $name);
        $addressConfig = config('setting.address_config');
        $servicePeriodConfig = config('setting.service_period_config');
        foreach ($lists as $lKey => $lValue) {
            $lists[$lKey]['address'] = $addressConfig[$lValue['address'] - 1]['label'];
            $lists[$lKey]['service_period'] = $servicePeriodConfig[$lValue['service_period'] - 1]['label'];

            // 处理：如果该工具没有子工具，则显示为“无”

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
        return new SuccessMessage();
    }

    public function getOneTool($id) {
        (new IDMustBePositiveInt())->goCheck();
        $tool = ToolModel::get($id);
        $result['data'] = $tool;
        $result['code'] = 200;
        $result['error_code'] = 20000;
        return $result;

    }
}