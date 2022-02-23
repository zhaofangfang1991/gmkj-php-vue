<?php
/**
 *Author: zff
 */

namespace app\api\model;


class Parts extends BaseModel
{
    /**
     * 添加备件信息-备件入库
    */
    public static function addParts($data) {
        $data['begin_time'] = date('Y-m-d H:i:s');
        $data['current_count'] = $data['count'];
        return self::create($data);
    }

    /**
     * 通过备件名称搜索
     */
    public static function getPartsLists($page = 1, $size) {
        $params = input();

        $map = array();
        if (array_key_exists('name', $params)and !empty($params['name'])) {
            $map['name'] = ['like', "%" . $params['name'] . "%"];
        }
        if (array_key_exists('limit', $params)) {
            $size = $params['limit'];
        }

        return self::where($map)->order('begin_time', 'desc')->order('count', 'desc')->paginate($size);
    }
}