<?php
/*
 * 宠物活动订单
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018-09-12 上午 9:31
 */
namespace app\portal\controller;

use app\portal\model\UserActivityModel;
use cmf\controller\AdminBaseController;

class AdminUserActivityController extends AdminBaseController
{
    public function index()
    {
        $keyword = $this->request->param('keyword','');
        $where = '';
        if(!empty($keyword)){
            $keyword = trim($keyword);
            $where['a.title'] = ['like',"%$keyword%"];
        }
        $user_activity = new UserActivityModel();
        $list = $user_activity
            ->field('a.id,u.user_nickname,u.mobile,a.title,activity_date,activity_place')
            ->alias('ua')
            ->join('__USERS__ u','u.id = ua.user_id')
            ->join('__ACTIVITY__ a', 'a.id = ua.activity_id')
            ->where($where)
            ->paginate(30);

        $this->assign('page',$list->render());
        $this->assign('list',$list);
        $this->assign('keyword',empty($keyword)?'':$keyword);
        return $this->fetch();
    }

}