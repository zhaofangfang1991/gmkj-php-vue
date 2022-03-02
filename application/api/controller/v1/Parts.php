<?php
/**
 *Author: zff
 */

namespace app\api\controller\v1;
use app\api\validate\IDMustBePositiveInt;
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

    public function addNum($id) {
        (new IDMustBePositiveInt())->goCheck();

        $parts = PartsModel::get($id);
        if (!$parts) {
            throw new PartsException([
                'code' => 404,
                'msg' => '备件信息错误，请检查参数',
                'errorCode' => 90011
            ]);
        }

        $addNum = input('post.num');
        if ($addNum <= 0) {
            throw new PartsException([
                'code' => 404,
                'msg' => '补货失败，请检查参数',
                'errorCode' => 90013
            ]);
        }
        PartsModel::where('id', $id)->setInc('count', $addNum); // 入库数量累加
        PartsModel::where('id', $id)->setInc('current_count', $addNum); //  + 当前所剩数量
        return new SuccessMessage();


    }
}