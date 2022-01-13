<?php
/**
 *Author: zff
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\model\Resource as ResourceModel;
use app\lib\exception\SuccessMessage;


class Resource extends BaseController
{
    public function deleteResource($id) {
        (new IDMustBePositiveInt())->goCheck();
        ResourceModel::destroy($id);
        return new SuccessMessage();
    }
}