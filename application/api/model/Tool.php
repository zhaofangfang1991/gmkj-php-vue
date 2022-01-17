<?php
/**
 *Author: zff
 */
namespace app\api\model;

use app\lib\exception\ToolException;
use app\api\model\Agency as agencyModel;
use app\api\model\Resource as resourceModel;

class Tool extends BaseModel
{
    public function subTool()
    {
        return $this->hasMany('Tool', 't_id', 'id');
    }

    public function urlLists()
    {
        return $this->hasMany('Resource', 'belongs_id', 'id');
    }

    public function chargeAccount() {
        return $this->belongsTo('Account', 'charge_id', 'id');
    }

    public function agency() {
        return $this->belongsTo('Agency', 'agency_id', 'id');
    }

    /**
     * 添加设备流程
     */
    public static function addTool($data) {

        // 1 整理数据
        if ($data['subSort']) {
            $subData['name'] = $data['subName'];
            $subData['no'] = $data['subNo'];
            $subData['sort'] = $data['subSort'];
            $subData['level'] = 1;
            unset($data['subName']);unset($data['subNo']);unset($data['subSort']);unset($data['subLevel']);
        }

        // 2 存当前工具
        $addResult = self::create($data);
        $t_id = $addResult->id;

        // 3 存子工具
        $subData['t_id'] = $t_id;
        $addSubResult = self::create($subData);

        // 4 更新文件
        if ($data['pics']) {
            foreach ($data['pics'] as $key => $value) {
                resourceModel::where('url', '=', $value['pic'])->update(['belongs_id' =>  $t_id, 'belongs' => 1]);
            }
        }

        return $t_id;
    }

    public static function getToolLists($page, $limit, $name) {
        $where['level'] = 0;
        if (isset($name) && !empty($name)) {
            $where['name'] = ['like', "%" . $name . "%"];
        }
       return self::with('subTool')->order('id', 'asc')->where($where)->paginate($limit);
    }

    public static function getOneToolByID($id) {
        return self::with('subTool,urlLists')->find($id);
    }


    public static function editTool($id, $data) {
        // 1 整理数据
        if ($data['subSort']) {
            $subData['name'] = $data['subName'];
            $subData['no'] = $data['subNo'];
            $subData['sort'] = $data['subSort'];
            $subData['level'] = 1;
            $subData['t_id'] = $id;
            unset($data['subName']);unset($data['subNo']);unset($data['subSort']);
        }
        // 2 存子工具
        $sub_id = self::where('t_id', '=', $id)->insert($subData, true);

        // 3 存当前工具
        $addResult = self::where('id', '=', $id)->update($data);

        // 4 更新文件
        if ($data['pics']) {
            foreach ($data['pics'] as $key => $value) {
                resourceModel::where('url', '=', $value['pic'])->update(['belongs_id' =>  $id, 'belongs' => 1]);
            }
        }
        return $id;
    }

}