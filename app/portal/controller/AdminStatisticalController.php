<?php
/*
 * 数据统计
 * 1寄养预约，2接送班车预约，3托儿所预约，4游泳预约，5美容预约，6萌主餐饮预约，7课程预约)
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018-09-14 下午 3:14
 */
namespace app\portal\controller;

use app\portal\model\OrderModel;
use cmf\controller\AdminBaseController;

class AdminStatisticalController extends AdminBaseController
{
    public function index()
    {
        $start_time = $this->request->param('start_time','');
        $end_time = $this->request->param('end_time','');
        if(!empty($start_time) and !empty($end_time)){
            $where['create_time'] = ['between',[$start_time,$end_time]];
        }else{
            $where = [];
        }
        $data1 = [];
        $data2 = [];
        //寄养预约
        $fostermum = $this->getOrderNum(1,$where);
        //接送班车预约
        $shuttlenum = $this->getOrderNum(2,$where);
        //托儿所预约
        $nurserynum = $this->getOrderNum(3,$where);
        //游泳预约
        $swimmum = $this->getOrderNum(4,$where);
        //美容预约
        $beautymum = $this->getOrderNum(5,$where);
        //餐饮预约
        $foodmum = $this->getOrderNum(6,$where);
        //课程预约
        $coursemum = $this->getOrderNum(7,$where);
        array_push($data1,$fostermum,$shuttlenum,$nurserynum,$swimmum,$beautymum,$foodmum,$coursemum);
        $total_num = $fostermum + $shuttlenum + $nurserynum + $swimmum + $beautymum + $foodmum + $coursemum;
        //寄养预约比例
        $fosterper = round($fostermum/$total_num*100);
        //接送班车预约比例
        $shuttleper = round($shuttlenum/$total_num*100);
        //托儿所预约比例
        $nurseryper = round($nurserynum/$total_num*100);
        //游泳预约比例
        $swimper = round($swimmum/$total_num*100);
        //美容预约比例
        $beautyper = round($beautymum/$total_num*100);
        //餐饮预约比例
        $foodper = round($foodmum/$total_num*100);
        //课程预约比例
        $courseper = round($coursemum/$total_num*100);
        array_push($data2,['value'=>$fosterper,'name'=>'寄养预约'],['value'=>$shuttleper,'name'=>'班车预约'],['value' =>$nurseryper,'name'=>'托儿所预约'],['value'=>$swimper,'name'=>'游泳预约'],['value'=>$beautyper,'name'=>'美容预约'],['value'=>$foodper,'name'=>'餐饮预约'],['value'=>$courseper,'name'=>'课程预约']);

        $this->assign('data1',json_encode($data1));
        $this->assign('data2',json_encode($data2));
        $this->assign('start_time',empty($start_time)?'':$start_time);
        $this->assign('end_time',empty($end_time)?'':$end_time);
        return $this->fetch();
    }

    private function getOrderNum($server_type,$where)
    {
        $order = new OrderModel();
        $num = $order
            ->where($where)
            ->where('server_type',$server_type)
            ->count();
        return $num;
    }

}