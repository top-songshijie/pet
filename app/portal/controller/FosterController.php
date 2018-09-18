<?php
/*
 * 寄养预约
 * 服务类型(1寄养预约，2接送班车预约，3托儿所预约，4游泳预约，5美容预约，6萌主餐饮预约，7课程预约)
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018-09-13 下午 3:54
 */
namespace app\portal\controller;

use app\portal\model\HouseModel;
use app\portal\model\PetsModel;
use cmf\controller\WeChatBaseController;
use think\Session;
use app\portal\service\TextService;

class FosterController extends WeChatBaseController
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
        $info = TextService::getOrderIntroduction(1);

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
        $pet = new PetsModel();
        //我的宠物列表
        $pet_list = $pet
            ->field('id as value,name as label')
            ->where('user_id',$this->user_id)
            ->select()
            ->toArray();
        //预约简介
        $info = TextService::getOrderIntroduction(1);
        //我的宠物
        $mypet = $pet->where([
            'user_id' => $this->user_id,
            'default' => 1
        ])->find();
        //入住说明
        $rzsm = TextService::getRzsm();
        //收费标准说明
        $sfbz = TextService::sfbz(1);

        $this->assign('info',$info);
        $this->assign('pet_list',json_encode($pet_list));
        $this->assign('mypet',$mypet);
        $this->assign('rzsm',$rzsm);
        $this->assign('sfbz',$sfbz);
        return $this->fetch();
    }

    /**
     * 宠物房型
     */
    public function pethouse()
    {
        $pet = new PetsModel();
        $house = new HouseModel();
        //1猫2狗
        $pet_type = $pet->where([
            'user_id' => $this->user_id,
            'default' => 1
        ])->value('type');
        $list = $house->where('pet_type',$pet_type)->select();

        $this->assign('list',$list);
        return $this->fetch();
    }
}