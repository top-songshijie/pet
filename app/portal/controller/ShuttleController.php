<?php
/*
 * 接送班车预约
 * 服务类型(1寄养预约，2接送班车预约，3托儿所预约，4游泳预约，5美容预约，6萌主餐饮预约，7课程预约)
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018-09-13 上午 10:01
 */
namespace app\portal\controller;

use cmf\controller\WeChatBaseController;
use think\Session;
use app\portal\service\TextService;

class ShuttleController extends WeChatBaseController
{
    protected $user_id;

    public function _initialize()
    {
        parent::_initialize();
        $this->user_id = Session::get('user.id');
    }
    /**
     * 预约介绍页面
     */
    public function index()
    {
        if($this->isBank()){
            return $this->fetch("bank/index");
        };
        $info = TextService::getOrderIntroduction(2);

        $this->assign('info',$info);
        return $this->fetch();
    }

    /**
     * 预约页面
     */
    public function order_detail()
    {
        if($this->isBank()){
            return $this->fetch("bank/index");
        };
        $info = TextService::getOrderIntroduction(2);

        return $this->fetch();
    }
}