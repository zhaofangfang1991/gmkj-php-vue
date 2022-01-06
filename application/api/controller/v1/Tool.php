<?php
/**
 *Author: zff
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
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
//        $data = input('post.');
        $result = ToolModel::addTool($data);

        // 3 判断model执行的结果
        if (!$result) {
            throw new ToolException();
        }

        // 4 返回json
        return new SuccessMessage();
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
}