<?php
// +----------------------------------------------------------------------
// | bronet [ 以客户为中心 以奋斗者为本 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.bronet.cn All rights reserved.
// +----------------------------------------------------------------------
namespace api\portal\controller;

use api\portal\model\PortalPostModel;
use cmf\controller\RestBaseController;
use api\portal\model\PortalTagModel;

class TagsController extends RestBaseController
{
    protected $tagModel;

    public function __construct(PortalTagModel $tagModel)
    {
        parent::__construct();
        $this->tagModel = $tagModel;
    }

    /**
     * 获取标签列表
     */
    public function index()
    {
        $params = $this->request->get();
        $data   = $this->tagModel->getDatas($params);
        $this->success('请求成功!', $data);
    }

    /**
     * 获取热门标签列表
     */
    public function hotTags()
    {
        $params                         = $this->request->get();
        $params['where']['recommended'] = 1;
        $data                           = $this->tagModel->getDatas($params);
        $this->success('请求成功!', $data);
    }

    /**
     * 获取标签文章列表
     * @param int $id
     */
    public function articles($id)
    {
        if (intval($id) === 0) {
            $this->error('无效的标签id！');
        } else {
            $params             = $this->request->param();
            $params['id']       = $id;
            $params['relation'] = 'articles';
            $postModel          = new PortalPostModel();

            $articles = $postModel->setCondition($params)->alias('a')->join('__PORTAL_TAG_POST__ tp', 'a.id = tp.post_id')
                ->where(['tag_id' => $id])->select();

            $this->success('请求成功!', ['articles' => $articles]);
        }
    }
}
