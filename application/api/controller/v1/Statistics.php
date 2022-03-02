<?php
/**
 *Author: zff
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\Account as AccountModel;
use app\api\model\Tool as ToolModel;
use app\api\model\Review as ReviewModel;
use app\api\model\Repair as RepairModel;
use app\api\model\Agency as AgencyModel;
use app\api\model\Maintenance as MaintenanceModel;
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

        // 今日待检
        $result['data']['today_review'] = ReviewModel::where('status', '=', 1)->count();
        // 供应商总数
        $result['data']['agent_count'] = AgencyModel::where('status', '=', 1)->count();
        // 累计报修
        $result['data']['repair_count'] = RepairModel::count();
        // 维修中工单数 repairing
        $result['data']['repairing'] = RepairModel::where('status', 1)->count();
        // 今日待完成保养任务数
        $result['data']['maintenance'] = MaintenanceModel::where('status', 1)->count();

        $result['error_code'] = 20000;
        return $result;
    }

    // 以设备状态为视角统计 正常运行中1 带病运行中2 停机3
    public function toolCount() {
        $toolCount = ToolModel::where('level', 0)->group('runing_status')->field('runing_status as name,count(*) as value')->select();
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

    // 获取维修次数最多的5种设备
    public function mostRepair() {
        $result['data'] = RepairModel::query("SELECT  tool_id, count(*) as num, `name` from `repair`  r LEFT 
        JOIN tool t  ON  t.id =  r.tool_id GROUP BY  tool_id ORDER BY  num asc limit 5;");
        $result['error_code'] = 20000;
        return $result;
    }

    // 获取领用次数最多的5种备件
    public function mostUseParts() {
        $result['data'] = Db::query("SELECT p_id, count(*) as num,p.`name` FROM parts_use pu 
        LEFT JOIN parts p ON p.id = pu.p_id GROUP BY p_id ORDER BY num asc limit 5;
        ");
        $result['error_code'] = 20000;
        return $result;
    }
}