<?php
/*
 * 萌宠活动
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018-09-18 上午 9:41
 */
namespace app\portal\controller;

use app\portal\model\ActivityModel;
use app\portal\model\UserActivityModel;
use cmf\controller\WeChatBaseController;
use think\Session;

class ActivityController extends WeChatBaseController
{
    protected $user_id;

    public function _initialize()
    {
        parent::_initialize();
        $this->user_id = Session::get('user.id');
    }

    public function index()
    {
        $activity = new ActivityModel();
        $list = $activity
            ->field('id,title,banner,status')
            ->order('id desc')
            ->select();

        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 详情
     */
    public function detail()
    {
        $id = $this->request->param('id','','intval');
        $activity = new ActivityModel();
        $user_activity = new UserActivityModel();
        $info = $activity->where('id',$id)->find();
        $info['content'] = htmlspecialchars_decode($info['content']);
        $if_exist = $user_activity->where([
            'user_id' => $this->user_id,
            'activity_id' => $id
        ])->find();
        if($if_exist){
            $info['if_baoming'] = 1;
        }else{
            $info['if_baoming'] = 0;
        }
        $this->assign('info',$info);
        return $this->fetch();
    }

    /**
     * 报名
     */
    public function baoming()
    {
        $activity_id = $this->request->param('id','','');
        $user_activity = new UserActivityModel();
        $if_exist = $user_activity->where([
            'user_id' => $this->user_id,
            'activity_id' => $activity_id
        ])->find();
        if($if_exist){
            $this->apiResponse(0,'您已报名此活动','');
        }
        $res = $user_activity->insert([
            'user_id' => $this->user_id,
            'activity_id' => $activity_id,
            'create_time' => date('Y-m-d H:i:s')
        ]);
        if($res){
            $this->apiResponse(1,'报名成功','');
        }
    }

}