<?php
/**
 * Author: zff
 * Date: 2017/2/20
 * Time: 1:34
 */

namespace app\api\model;


use think\Model;

class ProductImage extends BaseModel
{
    protected $hidden = ['img_id', 'delete_time', 'product_id'];
    public function imgUrl()
    {
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}