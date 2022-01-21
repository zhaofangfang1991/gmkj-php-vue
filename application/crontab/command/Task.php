<?php
/**
 *Author: zff
 */

namespace app\crontab\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\Db;
class Task extends Command
{
    protected function configure()
    {
        $this->setName('task')
            ->setDescription('定时计划测试：每天插入一条数据');
    }

    //命令： php think task
    protected function execute(Input $input, Output $output)
    {
        // 输出到日志文件
        $output->writeln("TestCommand:");

        // 1 处理往期巡检数据
        $this->doReviewStatus();

        // 2 给巡检初始数据
        $this->doList();
        $output->writeln("end....");
    }

    protected function doReviewStatus() {
        // 1 找到所有数据
        $expiredReviews = db('review')->where('status', '=', 1)->where('review_time', '<', date('Y-m-d'))
            ->where('review_id', '=', 0)->field('id')->select();

        // 2 循环处理：时间是过期时间 status = 1，则更改 status = 3 为过期未完成
        foreach ($expiredReviews as $k => $v) {
            db('review')->where('id', '=', $v['id'])->update(['status' => 3]);
        }
    }

        // 定时器需要执行的内容-循环执行插入-给每个设备新增一条巡检数据
    // TODO 这个方法存在巨大BUG，需要重新调整
    protected function doList() {

        // 1 找到所有需要巡检的设备ID
        $toolsID = db('tool')->where('level', '=', 0)->where('delete_time', 'NULL')->field('id')->select();
        var_dump($toolsID);

        // 2、先查一个看看是否已经有了
        $checkReviewOne = db('review')->where('review_time', '=', date('Y-m-d'))->where('t_id', '=', $toolsID[0]['id'])
            ->where('review_result', '=', 0)->where('status', '=', 1)->find();
        echo '这是我找的ID' . $toolsID[0]['id'] . '   ' . date('Y-m-d');
//        var_dump($checkReviewOne);
        echo 'sql: ' . db('review')->getLastSql();

        if ($checkReviewOne) {
            echo 'shoule be stop';
            exit();
        }

        // 3 给每个设备新增一条巡检数据
        foreach ($toolsID as $k => $v) {
            $data = [
                'review_time' => date('Y-m-d'),
                'review_result' => 0,
                't_id' => $v['id'],
                'status' => 1
            ];
            db('review')->insert($data);

        }
    }




}