<?php
/*
 * 其他预约（4游泳预约，5美容预约，6萌主餐饮预约，7课程预约）
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018-09-12 上午 11:25
 */
namespace app\portal\controller;

use cmf\controller\AdminBaseController;
use app\portal\model\OrderModel;

class AdminOtherController extends AdminBaseController
{
    public function index()
    {
        $server_type = $this->request->param('server_type','');
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
        //类型
        if(empty($server_type)){
            $where['o.server_type'] = ['in',[4,5,6,7]];
        }else{
            $where['o.server_type'] = $server_type;
        }
        $list = $order
            ->field('o.id,o.server_type,o.single_time,o.create_time,o.address,o.address_detail,u.user_nickname,u.mobile,p.name,p.strain,p.sex,p.weight')
            ->alias('o')
            ->join('__USERS__ u','u.id = o.user_id')
            ->join('__PETS__ p','p.id = o.pet_id','left')
            ->where($where)
            ->paginate(30);

        $this->assign('page',$list->render());
        $this->assign('list',$list);
        $this->assign('keyword',empty($keyword)?'':$keyword);
        $this->assign('start_time',empty($start_time)?'':$start_time);
        $this->assign('end_time',empty($end_time)?'':$end_time);
        $this->assign('server_type',empty($server_type)?'':$server_type);
        return $this->fetch();
    }

}