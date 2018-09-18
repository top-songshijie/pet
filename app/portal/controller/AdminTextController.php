<?php
/*
 * 文本相关
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018-09-11 上午 11:49
 */
namespace app\portal\controller;

use app\portal\model\TextModel;
use cmf\controller\AdminBaseController;

class AdminTextController extends AdminBaseController
{
    /**
     * 入住说明
     */
    public function rzsm()
    {
        $text = new TextModel();
        if($this->request->isPost()){
            $post = $this->request->param();
            $res = $text->update([
                'id' => $post['id'],
                'content' => $post['content'],
                'create_time' => date('Y-m-d H:i:s')
            ]);
            if($res){
                $this->success('配置信息更新成功');
            }
        }else{
            $info = $text
                ->field('id,content')
                ->where('type',1)
                ->find();
            $info['content'] = htmlspecialchars_decode($info['content']);

            $this->assign('info',$info);
            return $this->fetch();
        }
    }

    /**
     * 收费说明
     */
    public function sfsm()
    {
        $text = new TextModel();
        $list = $text->where('type',2)->select();

        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 收费说明详情以及提交
     */
    public function sfsmDetail()
    {
        $text = new TextModel();
        if($this->request->isPost()){
            $post = $this->request->param();
            $res = $text->update([
                'id' => $post['id'],
                'content' => $post['content'],
                'create_time' => date('Y-m-d H:i:s')
            ]);
            if($res){
                $this->success('配置信息更新成功');
            }
        }else{
            $id = $this->request->param('id','','intval');
            $info = $text
                ->field('id,content')
                ->find($id);
            $info['content'] = htmlspecialchars_decode($info['content']);

            $this->assign('info',$info);
            return $this->fetch();
        }
    }

    /**
     * 预约介绍
     */
    public function yyjs()
    {
        $text = new TextModel();
        $list = $text->where('type',3)->select();

        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 预约介绍详情以及提交
     */
    public function yyjsDetail()
    {
        $text = new TextModel();
        if($this->request->isPost()){
            $post = $this->request->param();
            $res = $text->update([
                'id' => $post['id'],
                'banner' => $post['banner'],
                'short_content' => $post['short_content'],
                'content' => $post['content'],
                'create_time' => date('Y-m-d H:i:s')
            ]);
            if($res){
                $this->success('配置信息更新成功');
            }
        }else{
            $id = $this->request->param('id','','intval');
            $info = $text
                ->field('id,banner,short_content,content')
                ->find($id);
            $info['content'] = htmlspecialchars_decode($info['content']);

            $this->assign('info',$info);
            return $this->fetch();
        }
    }

}