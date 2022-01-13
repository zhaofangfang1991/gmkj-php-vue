<?php
/**
 *Author: zff
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\Agency as agencyModel;
use app\api\validate\Agency as AgencyValidate;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\AgencyException;
use app\lib\exception\SuccessMessage;

class Agency extends BaseController
{
    public function getAllAgency($type= '' ,$page=1, $limit=20, $query='') {
        $result['lists'] = agencyModel::getAgencyLists($type, $page, $limit, $query);
        $result['error_code'] = 20000;
        return $result;
    }

    public function createAgency() {
        $validate = new AgencyValidate();
        $validate->goCheck();

        $data = $validate->getDataByRule(input('post.'));
        $result = agencyModel::addAgency($data);

        // 3 判断model执行的结果
        if (!$result) {
            throw new AgencyException();
        }

        // 4 返回json
        return new SuccessMessage();
    }

    public function getAgentOne($id) {
        (new IDMustBePositiveInt())->goCheck();
        $data = agencyModel::getOneToolByID($id);

        $result['data'] = $data;
        $result['code'] = 200;
        $result['error_code'] = 20000;
        return $result;
    }

    public function editAgency($id) {
        (new IDMustBePositiveInt())->goCheck();
        $agency = agencyModel::get($id);
        if (!$agency) {
            throw new AgencyException([
                'code' => 404,
                'msg' => '代理商不存在, 请检查参数',
                'errorCode' => 90007
            ]);
        }

        // 校验参数
        $validate = new AgencyValidate();
        $validate->goCheck();

        $uniqueReslt = $validate->isUniqueExceptMe('agency', $id, 'telnumber', input('post.')['telnumber']);
        if ($uniqueReslt) {
            throw new AgencyException([
                'code' => 404,
                'msg' => '手机号已存在',
                'errorCode' => 60004
            ]);
        }

        $data = $validate->getDataByRule(input('post.'));
        agencyModel::editAgency($id, $data);
        return new SuccessMessage();
    }

    public function deleteOne($id) {
        (new IDMustBePositiveInt())->goCheck();
        agencyModel::destroy($id);
        return new SuccessMessage();
    }
}

