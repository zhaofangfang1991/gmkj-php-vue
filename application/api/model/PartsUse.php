<?php
/**
 *Author: zff
 */

namespace app\api\model;
use app\api\model\Parts as PartsModel;


class PartsUse extends BaseModel
{
    public function useAccount() {
        return $this->belongsTo('Account', 'account_id', 'id')->field('id,username,telnumber');
    }
    /**
     * 领用备件
     * 在partsuse表中新增一条记录：领哪个备件，领之前余量，本次领几个，领之后剩余几个，谁领的
     * 更新parts表的current_count，=本次领用之后的余量
    */
    public static function editPartsUse($id, $data) {

        $data['p_id'] = $id;
        $data['surplus'] = $data['current_count'] - $data['num'];
        $data['use_time'] = date('Y-m-d H:i:s');

        self::create($data);
        // 更新parts表
        PartsModel::where('id', $id)->setInc('use_count', $data['num']); // 领用数量
        PartsModel::where('id', $id)->update(['current_count' => $data['surplus']]); // 更新字段

        return 0;
    }
}