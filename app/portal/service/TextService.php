<?php
/*
 * 文本服务
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018-09-13 下午 3:29
 */
namespace app\portal\service;

use app\portal\model\TextModel;
use cmf\controller\WeChatBaseController;

class TextService extends WeChatBaseController
{
    /**
     * 获取预约介绍内容
     */
    public static function getOrderIntroduction($server_type)
    {
        $text = new TextModel();
        $where['type'] = 3;
        $where['server_type'] = $server_type;
        $info = $text
            ->field('banner,short_content,content')
            ->where($where)
            ->find()
            ->toArray();
        $info['banner'] = cmf_get_image_url($info['banner']);
        $info['content'] = htmlspecialchars_decode($info['content']);
        return $info;
    }

    /**
     * 获取入住说明
     */
    public static function getRzsm()
    {
        $text = new TextModel();
        $where['type'] = 1;
        $info = $text
            ->where($where)
            ->value('content');
        $info = htmlspecialchars_decode($info);
        return $info;
    }

    /**
     * 获取收费标准说明
     */
    public static function sfbz($server_type)
    {
        $text = new TextModel();
        $where['type'] = 2;
        $where['server_type'] = $server_type;
        $info = $text
            ->where($where)
            ->value('content');
        $info = htmlspecialchars_decode($info);
        return $info;
    }

}