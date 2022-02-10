<?php
/**
 * 路由注册
 *
 * 以下代码为了尽量简单，没有使用路由分组
 * 实际上，使用路由分组可以简化定义
 * 并在一定程度上提高路由匹配的效率
 */

// 写完代码后对着路由表看，能否不看注释就知道这个接口的意义
use think\Route;


// 这里开始写
// 账号

// token 登录 退出
Route::post('api/:version/oauth/token', 'api/:version.Token/getAccountToken');
Route::get('api/:version/oauth/token', 'api/:version.Token/accountTokenInvalid');
Route::get('api/:version/user/info', 'api/:version.Token/getInfo');



// 1 账号管理：新增账号 列表搜索
Route::post('api/:version/users', 'api/:version.Account/createAccount');
Route::get('api/:version/users', 'api/:version.Account/getAllAccount');
Route::get('api/:version/users/charge', 'api/:version.Account/getChargeAccount');
// 账号删除、账号编辑
Route::delete('api/:version/user/:id', 'api/:version.Account/deleteOne');
Route::post('api/:version/user/:id', 'api/:version.Account/editAccount');

// 2 设备管理
Route::post('api/:version/tools', 'api/:version.Tool/createTool');
Route::get('api/:version/tools/servicePeriod', 'api/:version.Tool/getServicePeriod');
Route::get('api/:version/tools/addressConfig', 'api/:version.Tool/getAddressConfig');
Route::get('api/:version/tools', 'api/:version.Tool/getAllTool');
Route::delete('api/:version/tool/:id', 'api/:version.Tool/deleteOne');
Route::get('api/:version/tool/:id', 'api/:version.Tool/getOneTool');
Route::post('api/:version/tool/:id', 'api/:version.Tool/editTool');

// 3 代理商管理
Route::get('api/:version/agencys/[:type]', 'api/:version.Agency/getAllAgency');
Route::post('api/:version/agency', 'api/:version.Agency/createAgency');
Route::get('api/:version/agency/:id', 'api/:version.Agency/getAgentOne');
Route::post('api/:version/agency/:id', 'api/:version.Agency/editAgency');
Route::delete('api/:version/agency/:id', 'api/:version.Agency/deleteOne');


// 4 文件上传
Route::post('api/:version/upload', 'api/:version.Upload/uploadFile');
Route::delete('api/:version/resource/:id', 'api/:version.Resource/deleteResource');

// 5 巡检管理
Route::get('api/:version/reviews', 'api/:version.Review/getAllReview');
Route::get('api/:version/review/:id', 'api/:version.Review/getOneReview');
Route::post('api/:version/review/:id', 'api/:version.Review/editReview');


//  6 统计
Route::get('api/:version/statistics', 'api/:version.Statistics/statisticsData');
Route::get('api/:version/toolcount', 'api/:version.Statistics/toolCount');
Route::get('api/:version/reviewcount', 'api/:version.Statistics/reviewCount'); // 柱状图 巡检数据的统计
