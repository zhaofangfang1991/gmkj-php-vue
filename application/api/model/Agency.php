<?php
/**
 *Author: zff
 */

namespace app\api\model;


class Agency extends BaseModel
{
    protected $visible = ['id','username','telnumber','gender','agent','manufacturer'];
    public static function getAgencyLists($type, $page, $limit, $query) {
        if ($type == 1) {
            return self::select();
        } else {
            return self::where('username|telnumber|manufacturer', 'like', '%' . $query . "%")->paginate($limit);
        }
    }

    public static function addAgency($data) {
        self::create($data);
        return self::getLastInsID();
    }

    public static function getOneToolByID($id) {
        return self::find($id);
    }

    public static function editAgency($id, $data) {
        return self::where('id', '=', $id)->update($data);
    }
}