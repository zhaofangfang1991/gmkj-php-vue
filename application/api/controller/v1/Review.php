<?php
/**
 *Author: zff
 */

namespace app\api\controller\v1;
use app\api\controller\BaseController;
use app\api\model\Review as ReviewModel;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\Review as ReviewValidate;
use app\lib\exception\SuccessMessage;


class Review extends BaseController
{
    public function getAllReview() {
        $lists = ReviewModel::getLists($page = 1, $size = 30);

        $result['error_code'] = 20000;
        $result['lists'] = $lists;
        return $result;
    }


    public function getOneReview($id) {
        (new IDMustBePositiveInt())->goCheck();
        $review = ReviewModel::getOneReviewByID($id);

        $result['data'] = $review;
        $result['code'] = 200;
        $result['error_code'] = 20000;
        return $result;
    }

    public function editReview($id) {
        (new IDMustBePositiveInt())->goCheck();

        $review = ReviewModel::get($id);
        if (!$review) {
            throw new ToolException([
                'code' => 404,
                'msg' => '巡检原数据不存在，请检查参数',
                'errorCode' => 90008
            ]);
        }

        // 校验参数
        $validate = new ReviewValidate();
        $validate->goCheck();

        // 2 和model交互，去写具体的流程
        $data = $validate->getDataByRule(input('post.'));
        ReviewModel::editReview($id, $data);
        return new SuccessMessage();
    }
}