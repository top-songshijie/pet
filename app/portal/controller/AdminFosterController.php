<?php
/*
 * 寄养预约管理
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018-09-12 上午 9:58
 */
namespace app\portal\controller;

use app\portal\model\OrderModel;
use cmf\controller\AdminBaseController;

class AdminFosterController extends AdminBaseController
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
        //寄养预约
        $where['o.server_type'] = 1;
        $list = $order
            ->field('o.id,o.start_date,o.end_date,o.create_time,u.user_nickname,u.mobile,p.name,p.strain,p.sex,p.weight,h.title')
            ->alias('o')
            ->join('__USERS__ u','u.id = o.user_id')
            ->join('__PETS__ p','p.id = o.pet_id')
            ->join('__HOUSE__ h','h.id = o.house_id')
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