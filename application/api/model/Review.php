<?php
/**
 *Author: zff
 */

namespace app\api\model;

use app\api\service\Token;


class Review extends BaseModel
{

    // 与设备的关联模型
    public function tool() {
        return $this->belongsTo('Tool', 't_id', 'id')->field('id,name,no');
    }

    // 与account表的关联模型
    public function reviewAccount() {
        return $this->belongsTo('Account', 'review_id', 'id')->field('id,username,telnumber');
    }

    /**
    *review_time: "", 巡检时间
    tool: '', 对应的设备名称
    status: '', 巡检状态
     *
     */

    public static function getLists($page = 1, $size) {
        $params = input();

        $map = array();
        if (array_key_exists('review_time', $params) and !empty($params['review_time'])) {
            $map['review_time'] = $params['review_time'] ;
        }
        if (array_key_exists('status', $params) and !empty($params['status'])) {
            $map['status'] = $params['status'];
        }

        $tool['name'] = ['like', "%%"];
        if (array_key_exists('tool', $params)and !empty($params['tool'])) {
            $tool['name'] = ['like', "%" . $params['tool'] . "%"];
        }
        if (array_key_exists('limit', $params)) {
            $size = $params['limit'];
        }
//        echo self::getLastSql();
        return self::hasWhere('tool',['name'=>$tool['name']])->where($map)->with('tool,reviewAccount')->order('status', 'asc')->paginate($size);
    }

    public static function getOneReviewByID($id) {
        return self::with('tool,reviewAccount')->find($id);
    }

    public static function editReview($id, $data) {

        // 通过token获取当前登录用户的id
        $uid = Token::getCurrentUid();
        $data['review_id'] = $uid;

        $data['review_time'] = date('Y-m-d H:i:s');
        $data['status'] = 2;

        self::where('id', '=', $id)->update($data);

        return 0;
    }
}