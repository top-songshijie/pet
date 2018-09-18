<?php
/*
 * 个人中心
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018-09-18 上午 9:42
 */
namespace app\portal\controller;

use app\portal\model\ActivityModel;
use app\portal\model\ContactModel;
use app\portal\model\PetsModel;
use app\portal\model\UserActivityModel;
use app\portal\model\UsersModel;
use cmf\controller\WeChatBaseController;
use think\Db;
use think\Session;

class MineController extends WeChatBaseController
{
    protected $user_id;

    public function _initialize()
    {
        parent::_initialize();
        $this->user_id = Session::get('user.id');
    }

    public function index()
    {
        $pet = new PetsModel();
        $user = new UsersModel();
        $contact = new ContactModel();
        $user_info = $user->field('user_nickname,avatar')->where('id',$this->user_id)->find();
        $pet_info = $pet->field('name,avatar')->where([
            'user_id' => $this->user_id,
            'default' => 1
        ])->find();
        $pet_list = $pet
            ->field('id as value,name as label')
            ->where('user_id',$this->user_id)
            ->select()->toArray();
        $contact_info = $contact
            ->field('mobile,code')
            ->where('id',1)
            ->find();

        $this->assign('user_info',$user_info);
        $this->assign('pet_info',$pet_info);
        $this->assign('pet_list',json_encode($pet_list));
        $this->assign('contact_info',$contact_info);
        return $this->fetch();
    }

    /**
     * 切换默认宠物
     */
    public function cut_pet()
    {
        if($this->request->isAjax()){
            $id = $this->request->param('id','','intval');
            $pet = new PetsModel();
            Db::startTrans();
            $res = $pet->where('user_id',$this->user_id)->setField('default',0);
            $res2 = $pet->where('id',$id)->setField('default',1);
            if(!$res or !$res2){
                Db::rollback();
                $this->apiResponse(0,'error','');
            }
            Db::commit();
            $pet_info = $pet->field('name,avatar')->where('id',$id)->find()->toArray();
            $pet_info['avatar'] = cmf_get_image_preview_url($pet_info['avatar']);
            $this->apiResponse(1,'ok',$pet_info);
        }
    }


    /**
     * 我的订单
     */
    public function my_order()
    {
        return $this->fetch();
    }

    /**
     * 我的萌宠
     */
    public function my_pet()
    {
        return $this->fetch();
    }

    /**
     * 我的活动
     */
    public function my_activity()
    {
        $user_activity = new UserActivityModel();
        $list = $user_activity
            ->field('a.id,title,banner,activity_date,status')
            ->alias('ua')
            ->join('__ACTIVITY__ a','a.id = ua.activity_id')
            ->where('ua.user_id',$this->user_id)
            ->select();

        $this->assign('list',$list);
        return $this->fetch();
    }
}