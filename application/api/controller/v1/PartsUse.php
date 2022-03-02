<?php
/**
 *Author: zff
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\model\Parts as PartsModel;
use app\lib\exception\PartsException;
use app\api\validate\PartsUse as PartsUseValidate;
use app\api\model\PartsUse as PartsUseModel;
use app\lib\exception\SuccessMessage;

class PartsUse extends BaseController
{
    public function editPartsUse($id) {
        (new IDMustBePositiveInt())->goCheck();

        $parts = PartsModel::get($id);
        if (!$parts) {
            throw new PartsException([
                'code' => 404,
                'msg' => '备件不存在，请检查参数',
                'errorCode' => 90011
            ]);
        }

        // 1校验参数
        $validate = new PartsUseValidate();
        $validate->goCheck();

        // 2 和model交互，去写具体的流程
        $data = $validate->getDataByRule(input('post.'));

        if ($parts['current_count'] < $data['num']) {
            throw new PartsException([
                'code' => 404,
                'msg' => '备件余量不足',
                'errorCode' => 90012
            ]);
        }
        $data['current_count'] = $parts['current_count'];
        PartsUseModel::editPartsUse($id, $data);
        return new SuccessMessage();
    }
}