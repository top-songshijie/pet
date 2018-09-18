<?php
/*
 * 会员
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018-09-13 下午 3:29
 */
namespace app\portal\service;

use app\portal\model\UsersModel;
use cmf\controller\HomeBaseController;

class TextService extends HomeBaseController
{
    /**
     * 获取用户信息
     */
    public static function getUserMessage($user_id)
    {
        $user = new UsersModel();
        $info = $user
            ->field('user_nickname,avatar,mobile')
            ->find($user_id)
            ->toArray();
        return $info;
    }

}