<?php
/*
 * 接送班车预约
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018-09-12 上午 10:53
 */
namespace app\portal\controller;

use app\portal\model\OrderModel;
use cmf\controller\AdminBaseController;

class AdminShuttleController extends AdminBaseController
{
    public function index()
    {
        $keyword = $this->request->param('keyword','');
        $start_time = $this->request->param('start_time','');
        $end_time = $this->request->param('end_time','');
        if(!empty($keyword)){
            $keyword = trim($keyword);
            $where['user_nickname|mobile'] = ['like',"%$keyword%"];
        }
        if(!empty($start_time) and !empty($end_time)){
            $where['o.create_time'] = ['between',[$start_time,$end_time]];
        }
        $order = new OrderModel();
        //接送班车预约
        $where['o.server_type'] = 2;
        $list = $order
            ->field('o.id,o.single_time,o.create_time,o.address,o.address_detail,u.user_nickname,u.mobile,p.name,p.strain,p.sex,p.weight')
            ->alias('o')
            ->join('__USERS__ u','u.id = o.user_id')
            ->join('__PETS__ p','p.id = o.pet_id')
            ->where($where)
            ->paginate(30);

        $this->assign('page',$list->render());
        $this->assign('list',$list);
        $this->assign('keyword',empty($keyword)?'':$keyword);
        $this->assign('start_time',empty($start_time)?'':$start_time);
        $this->assign('end_time',empty($end_time)?'':$end_time);
        return $this->fetch();
    }

}