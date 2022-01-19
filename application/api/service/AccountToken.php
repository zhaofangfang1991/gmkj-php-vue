<?php
/**
 * Author: zff
 * Date: 2017/2/25
 * Time: 17:21
 */

namespace app\api\service;
use app\api\model\Account;
use app\lib\exception\TokenException;
use think\Exception;

class AccountToken extends Token
{
    public function get($ac, $se)
    {
        $app = Account::check($ac, $se);
        if(!$app) {
            throw new TokenException([
                'msg' => '账号密码错误，请重试',
                'errorCode' => 10004,
                'code' => 200
            ]);
        } else{
            $scope = $app->type;
            $uid = $app->id;
            $realusername = $app->username;
            $values = [
                'scope' => $scope,
                'uid' => $uid
            ];
            $token = $this->saveToCache($values);
            return [$token, $realusername];
        }
    }
    
    private function saveToCache($values){
        $token = self::generateToken();
        $expire_in = config('setting.token_expire_in');
        $result = cache($token, json_encode($values), $expire_in);
        if(!$result){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $token;
    }
}