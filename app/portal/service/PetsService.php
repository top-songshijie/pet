<?php
/*
 * 宠物
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018-09-13 下午 3:29
 */
namespace app\portal\service;

use app\portal\model\PetsModel;
use cmf\controller\HomeBaseController;

class TextService extends HomeBaseController
{
    /**
     * 获取默认宠物信息
     */
    public static function getUserMessage($user_id)
    {
        $pet = new PetsModel();
        $info = $pet
            ->field('name,avatar')
            ->where([
                'user_id' => $user_id,
                'default' => 1
            ])->find()->toArray();
        return $info;
    }

}