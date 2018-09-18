<?php
/*
 * 萌宠活动
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018-09-11 下午 4:13
 */
namespace app\portal\controller;

use app\portal\model\ActivityModel;
use cmf\controller\AdminBaseController;

class AdminActivityController extends AdminBaseController
{
    public function index()
    {
        $keyword = $this->request->param('keyword','');
        $start_time = $this->request->param('start_time','');
        $end_time = $this->request->param('end_time','');
        $activity = new ActivityModel();
        $where = [];
        if(!empty($keyword)){
            $keyword = trim($keyword);
            $where['title'] = ['like',"%$keyword%"];
        }
        if(!empty($start_time) and !empty($end_time)){
            $where['activity_date'] = ['between',[$start_time,$end_time]];
        }
        $list = $activity
            ->where($where)
            ->order('id DESC')
            ->paginate(30);

        $this->assign('page',$list->render());
        $this->assign('list',$list);
        $this->assign('keyword',empty($keyword)?'':$keyword);
        $this->assign('start_time',empty($start_time)?'':$start_time);
        $this->assign('end_time',empty($end_time)?'':$end_time);
        return $this->fetch();
    }

    /**
     * 添加及提交
     */
    public function add()
    {
        if($this->request->isPost()){
            $param = $this->request->param();
            $activity = new ActivityModel();
            if(!isset($param['status'])){
                $param['status'] = 0;
            }
            $param['create_time'] = date('Y-m-d H:i:s');
            $res = $activity->insert($param);
            if($res){
                $this->success('添加成功');
            }
        }else{
            return $this->fetch();
        }
    }

    /**
     * 编辑及提交
     */
    public function edit()
    {
        $activity = new ActivityModel();
        if($this->request->isPost()){
            $param = $this->request->param();
            if(!isset($param['status'])){
                $param['status'] = 0;
            }
            $param['create_time'] = date('Y-m-d H:i:s');
            $res = $activity->update($param);
            if($res){
                $this->success('数据更新成功');
            }
        }else{
            $id = $this->request->param('id','','intval');
            $info = $activity->find($id);
            $info['content'] = htmlspecialchars_decode($info['content']);

            $this->assign('info',$info);
            return $this->fetch();
        }
    }

}