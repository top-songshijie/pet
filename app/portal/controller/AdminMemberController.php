<?php
/*
 * 会员相关
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018-09-11 上午 11:53
 */
namespace app\portal\controller;

use app\portal\model\PetsModel;
use app\portal\model\StrainModel;
use app\portal\model\UsersModel;
use cmf\controller\AdminBaseController;

class AdminMemberController extends AdminBaseController
{
    public function index()
    {
        $keyword = $this->request->param('keyword','');
        if(!empty($keyword)){
            $where['id|user_nickname|mobile'] = ltrim($keyword,'');
        }
        $user = new UsersModel();
        $list = $user
            ->field('id,avatar,user_nickname,create_time,last_login_time,mobile')
            ->where(isset($where)?$where:'')
            ->order('create_time DESC')
            ->paginate(50);

        $this->assign('page',$list->render());
        $this->assign('list',$list);
        $this->assign('keyword',empty($keyword)?'':$keyword);
        return $this->fetch();
    }

    /**
     * 萌宠
     */
    public function pets()
    {
        $user_id = $this->request->param('id','','intval');
        $pets = new PetsModel();
        $list = $pets
            ->where('user_id',$user_id)
            ->select();

        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 宠物品种
     */
    public function strain()
    {
        $strain = new StrainModel();
        $list = $strain->select();

        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 品种详情
     */
    public function strainDetail()
    {
        $strain = new StrainModel();
        if($this->request->isPost()){
            $post = $this->request->param();
            $post['create_time'] = date('Y-m-d H:i:s');
            $res = $strain->update($post);
            if($res){
                $this->success('配置更新成功');
            }
        }else{
            $id = $this->request->param('id','','intval');
            $info = $strain->find($id);

            $this->assign('info',$info);
            return $this->fetch();
        }
    }

    /**
     * 添加品种
     */
    public function addStrain()
    {
        if($this->request->isPost()){
            $post = $this->request->param();
            $strain = new StrainModel();
            $post['create_time'] = date('Y-m-d H:i:s');
            $res = $strain->insert($post);
            if($res){
                $this->success('添加成功');
            }
        }else{
            return $this->fetch();
        }
    }

}