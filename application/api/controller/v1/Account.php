<?php
/**
 *Author: zff
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\Account as AccountModel;
use app\api\validate\AccountNew;
use app\api\validate\AccountUpdate;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\AccountException;
use app\lib\exception\SuccessMessage;
use think\Db;
use think\Exception;

class Account extends BaseController
{
    /**
     * 创建账号
     * params: post传过来的多个参数
    */
    public function createAccount() {
        // 1 验证参数
        $validate = (new AccountNew());
        $validate->goCheck();

        // 2 和数据库交互，写入数据库
        $data = $validate->getDataByRule(input('post.'));
        $result = AccountModel::addAccount($data);
        if (!$result) {
            throw new AccountException();
        }
        return json(new SuccessMessage());
    }

    /**
     * 用户列表
    */
    public function getAllAccount() {
        $lists = AccountModel::getAccountLists($page = 1, $size = 30);

        $result['error_code'] = 20000;
        $result['lists'] = $lists;
        return $result;
    }

    public function deleteOne($id) {
        (new IDMustBePositiveInt())->goCheck();
        // TODO 需要权限控制
        AccountModel::destroy($id);
        return new SuccessMessage();
    }

    public function editAccount() {
        // 验证参数
        $id = input('id');
        $telnumber = input('post.telnumber');
        if (!$id) {
            throw new AccountException([
                'code' => 404,
                'msg' => '必要参数ID不存在，请检查参数',
                'errorCode' => 60001
            ]);
        }

        $account = AccountModel::get($id);
//        echo self::getLastSql();
        if (!$account) {
            throw new AccountException([
                'code' => 404,
                'msg' => '用户不存在, 请检查参数',
                'errorCode' => 60003
            ]);
        }

        $validate = new AccountUpdate();
        $validate->goCheck();

        $uniqueReslt = $validate->isUniqueExceptMe('account', $id, 'telnumber', $telnumber);
        if ($uniqueReslt) {
            throw new AccountException([
                'code' => 404,
                'msg' => '手机号已存在',
                'errorCode' => 60004
            ]);
        }

        // TODO 这个方法只允许有权限的人操作；所以，在这里要判断操作者的权限；不符合则抛出异常


        $data = $validate->getDataByRule(input());
        AccountModel::editOne($id, $data);
        return new SuccessMessage();
    }

    /**
     * 获取有设备管理员权限的账号列表
    */
    public function getChargeAccount() {
        $lists = Db::query("SELECT id,username,telnumber FROM account WHERE delete_time is NULL and type&1;");
        return $lists;
    }
}