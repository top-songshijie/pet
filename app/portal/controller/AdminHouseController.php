<?php
/*
 * 宠物房型
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018-09-12 下午 2:07
 */
namespace app\portal\controller;

use app\portal\model\HouseModel;
use cmf\controller\AdminBaseController;

class AdminHouseController extends AdminBaseController
{
    public function index()
    {
        $pet_type = $this->request->param('pet_type','','intval');
        $keyword = $this->request->param('keyword','');
        $where = '';
        if(!empty($pet_type)){
            $where['pet_type'] = $pet_type;
        }
        if(!empty($keyword)){
            $keyword = trim($keyword);
            $where['title'] = ['like',"%$keyword%"];
        }
        $house = new HouseModel();
        $list = $house
            ->where($where)
            ->paginate(30);

        $this->assign('page',$list->render());
        $this->assign('list',$list);
        $this->assign('pet_type',empty($pet_type)?'':$pet_type);
        $this->assign('keyword',empty($keyword)?'':$keyword);
        return $this->fetch();
    }

    /**
     * 新添及提交
     */
    public function add()
    {
        if($this->request->isPost()){
            $param = $this->request->param();
            $house = new HouseModel();
            $param['create_time'] = date('Y-m-d H:i:s');
            $res = $house->insert($param);
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
        $house = new HouseModel();
        if($this->request->isPost()){
            $param = $this->request->param();
            $param['create_time'] = date('Y-m-d H:i:s');
            $res = $house->update($param);
            if($res){
                $this->success('添加成功');
            }
        }else{
            $id = $this->request->param('id','','intval');
            $info = $house->find($id);

            $this->assign('info',$info);
            return $this->fetch();
        }
    }

}