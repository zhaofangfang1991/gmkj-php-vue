<?php
/**
 *Author: zff
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\Account as AccountModel;
use app\api\model\Tool as ToolModel;
use app\api\model\Review as ReviewModel;
use think\Db;

class Statistics extends BaseController
{
    // 获取四大统计数据： 当前人员总数 设备总数 累计巡检次数 过期未巡检总数
    public function statisticsData() {
        // 当前人员总数
        $result['data']['account'] = AccountModel::where('id', '>', 1)->count();

        // 设备总数
        $result['data']['tool'] = ToolModel::where('level', '0')->count();

        // 累计巡检次数
        $result['data']['review'] = ReviewModel::where('status', '=', 2)->count();

        // 过期未巡检总数
        $result['data']['overreview'] = ReviewModel::where('status', '=', 3)->count();

        $result['error_code'] = 20000;
        return $result;
    }

    // 以设备状态为视角统计 正常运行中1 带病运行中2 停机3
    public function toolCount() {
        $toolCount = ToolModel::group('runing_status')->field('runing_status as name,count(*) as value')->select();
        $config = config('setting.runing_status');
        foreach ($toolCount as $k => $v) {
            $toolCount[$k]['name'] =  $config[$v['name']];
        }
        $result['data'] = $toolCount;
        $result['error_code'] = 20000;
        return $result;
    }

    public function reviewCount() {
        // 倒数5天的时间

        $count['dataString'] = array();
        for ($i = 5; $i > 0; $i--) {
            array_unshift($count['dataString'], date("Y-m-d",strtotime("-" . $i . " day")));
        }
        // 倒数5天的所有已完成数据
        // 倒数5天的所有未完成数据
        foreach ($count['dataString'] as $k => $v) {
            $count['complated'][] = ReviewModel::where('review_time', 'like', "%".$v."%")->where('status', '2')->count(); // 已完成
            $count['over'][] = ReviewModel::where('review_time', 'like', "%".$v."%")->where('status', '3')->count(); // 过期未完成
        }
        $result['data'] = $count;
        $result['error_code'] = 20000;
        return $result;
    }
}