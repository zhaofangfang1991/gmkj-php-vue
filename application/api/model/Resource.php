<?php
/**
 *Author: zff
 */

namespace app\api\model;


class Resource extends BaseModel
{
    protected $visible = ['id', 'type', 'url' ,'name'];

    public function getUrlAttr($value, $data)
    {
        return $this->prefixImgUrlResource($value, $data);
    }
}