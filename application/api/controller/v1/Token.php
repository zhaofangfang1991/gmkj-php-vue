<?php
/**
 * Author: zff
 * Date: 2017/2/21
 * Time: 12:23
 */

namespace app\api\controller\v1;


use app\api\service\AppToken;
use app\api\service\UserToken;
use app\api\service\Token as TokenService;
use app\api\service\AccountToken;
use app\api\validate\AppTokenGet;
use app\api\validate\AccountTokenGet;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;
use app\api\model\Account as AccountModel;
use app\lib\exception\TokenException;
use think\Cache;
use think\Request;

/**
 * 获取令牌，相当于登录
 */
class Token
{
    /**
     * 用户获取令牌（登陆）
     * @url /token
     * @POST code
     * @note 虽然查询应该使用get，但为了稍微增强安全性，所以使用POST
     */
    public function getToken($code='')
    {
        (new TokenGet())->goCheck();
        $wx = new UserToken($code);
        $token = $wx->get();
        return [
            'token' => $token
        ];
    }

    /**
     * 第三方应用获取令牌
     * @url /app_token?
     * @POST ac=:ac se=:secret
     */
    public function getAppToken($username='', $password='')
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');
        (new AppTokenGet())->goCheck();
        $app = new AppToken();
        $token = $app->get($username, $password);
        return [
            'token' => $token
        ];
    }

    public function verifyToken($token='')
    {
        if(!$token){
            throw new ParameterException([
                'token不允许为空'
            ]);
        }
        $valid = TokenService::verifyToken($token);
        return [
            'isValid' => $valid
        ];
    }

    /**
     * 账号密码登录 返回token
     * 可用   账号  密码 登录
     * 也可用 手机号 密码 登录
     * $username 有可能是账号，也有可能是手机号
     */
    public function getAccountToken($username='', $password='')
    {
        (new AccountTokenGet())->goCheck();
        $app = new AccountToken();
        $result = $app->get($username, $password);
        $token = $result[0];
        $realusername = $result[1];
        return [
            'error_code' => 20000,
            'token' => $token,
            'username' => $realusername
        ];
    }

    public function accountTokenInvalid($ac='') {
        cookie('token' , null);
        return [
            'error_code' => 20000,
            'msg' => '退出成功'
        ];
    }

    // 通过token获取用户信息
    public function getInfo() {

        $token = Request::instance()
            ->header('token');
        $identities = Cache::get($token);
        if (!$identities)
        {
            throw new TokenException();
        }
        else {
            $identities = json_decode($identities, true);
//            var_dump($identities);

            $config = [
                8 => ['admin'],
                4 => ['fix'],
                2 => ['review'],
                1 => ['charge'],
                12 => ['admin', 'fix'],
                10 => ['admin', 'review'],
                9 => ['admin', 'charge'],
                6 => ['fix', 'review'],
                5 => ['fix', 'charge'],
                3 => ['review', 'charge'],
                14 => ['admin', 'fix', 'review'],
                13 => ['admin', 'fix', 'charge'],
                11 => ['admin', 'review', 'charge'],
                7 => ['fix', 'review', 'charge'],
                15 => ['admin', 'fix', 'review', 'charge']
            ];

            // 当前登录者的名字
            $auth = AccountModel::field('username')->find($identities['uid']);

            $data = [
                'roles' => $config[$identities['scope']],
                'introduction' => $auth['username'],
                'avatar' => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
                'name' => $auth['username'],
            ];
            $result['data'] = $data;

            return [
                'error_code' => 20000,
                'data' => $data
            ];
        }
    }
}