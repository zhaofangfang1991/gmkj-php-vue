<?php
/**
 *Author: zff
 */

namespace app\api\controller\v1;
use app\api\validate\Parts as PartsValidate;
use app\api\model\Parts as PartsModel;
use app\lib\exception\PartsException;
use app\api\controller\BaseController;
use app\lib\exception\SuccessMessage;

class Parts extends BaseController
{
    public function createParts() {
        // 1 校验参数
        $validate = new PartsValidate();
        $validate->goCheck();

        // 2 和model交互，去写具体的流程
        $data = $validate->getDataByRule(input('post.'));
        $result = PartsModel::addParts($data);

        // 3 判断model执行的结果
        if (!$result) {
            throw new PartsException();
        }

        // 4 返回json
        return new SuccessMessage();
    }


    public function getAllParts() {
        $lists = PartsModel::getPartsLists($page = 1, $size = 30);

        $result['lists'] = $lists;
        $result['error_code'] = 20000;
        $result['code'] = 200;
        return $result;
    }
}