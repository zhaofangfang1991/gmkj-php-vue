<?php
/**
 * Author: zff
 * Date: 2017/2/23
 * Time: 22:04
 */

namespace app\lib\enum;

/**
 * 接口访问权限枚举
 * 这种权限涉及是逐级式
 * 简单，但不够灵活
 * 最完整的权限控制方式是作用域列表式权限
 * 给每个令牌划分到一个SCOPE作用域，每个作用域
 * 可访问多个接口
 */

class ScopeEnum
{
    const User = 16;
    // 管理员是给CMS准备的权限
    const Super = 32;

    public $admin = [8,12,10,9,14,11,15]; // 管理员
    public $inspection = [4,6,5,7,15]; // 巡检人员
    public $repair = [2,10,6,14,7,11,15]; // 维修人员
    public $charge = [1,9,5,3,7,11,15]; // 设备负责人

}