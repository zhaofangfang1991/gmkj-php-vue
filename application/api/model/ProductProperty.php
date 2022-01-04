<?php
/**
 * Author: zff
 * Date: 2017/2/20
 * Time: 2:01
 */

namespace app\api\model;


class ProductProperty extends BaseModel
{
    protected $hidden=['product_id', 'delete_time', 'id'];
}