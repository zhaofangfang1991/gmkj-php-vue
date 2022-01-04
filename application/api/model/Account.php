<?php
/**
 *Author: zff
 */

namespace app\api\model;


use app\lib\exception\AccountException;
use think\Exception;

class Account extends BaseModel
{
    protected $deleteTime = 'delete_time';

    /*
        select * from account where (username = $ac and app_secret = )
    */
    public static function check($ac, $se)
    {
        $se = self::setHash($se);
        $app = self::where('password_hash', '=', $se)
            ->where('telnumber|username','=',$ac)
            ->find();
        return $app;
    }

    /**
     * 处理密码hash值
     * 接收一个密码值，通过盐转成hash值
    */
    public static function setHash($se) {
        return sha1(config('password_salt') . $se);
    }

    /**
     * 验证密码
     * 判断 hash(密码 和 盐) 是否等于 数据库的hash
    */
    public static function checkPassword($se) {


    }

    /**
     * $data: 经过验证的所有POST数据
     * 拿到姓名、手机号、密码、用户类型、性别  然后存到数据库
    */
    public static function addAccount($data) {
        $data['password_hash'] = self::setHash($data['password']);
        unset($data['password']);
//        var_dump($data['role_type']);
//        var_dump(implode(',', $data['role_type']));


        $result = self::create([
            'username'  =>  $data['username'],
            'telnumber' =>  $data['telnumber'],
            'password_hash' =>  $data['password_hash'],
            'gender' =>  $data['gender'],
            'type' =>  $data['type'],
            'role_type' => implode(',', $data['role_type']),
            'status' => '正常'
        ]);
        return $result;
    }

    /**
     * 列表查询
     * params: username  telnumber
    */
    public static function getAccountLists($page = 1, $size) {
        $params = input();

        $map = array();
        if (array_key_exists('username', $params) and !empty($params['username'])) {
            $map['username'] = ['like', "%" . $params['username'] . "%"];
        }
        if (array_key_exists('telnumber', $params)and !empty($params['telnumber'])) {
            $map['telnumber'] = $params['telnumber'];
        }
        if (array_key_exists('limit', $params)) {
            $size = $params['limit'];
        }
//        echo self::getLastSql();
        return self::where($map)->paginate($size);
    }

    /**
     * 这个方法用来编辑用户账号
    */
    public static function editOne($id, $data) {
        if (array_key_exists('password', $data)) {
            $data['password_hash'] = self::setHash($data['password']);
            unset($data['password']);
        }
        $data['role_type'] = implode(',', $data['role_type']);
        self::where('id', '=', $id)->update($data);
        return ;
    }
}