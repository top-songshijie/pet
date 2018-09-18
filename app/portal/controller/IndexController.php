<?php
// +----------------------------------------------------------------------
// | bronet [ 以客户为中心 以奋斗者为本 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.bronet.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
namespace app\portal\controller;

use cmf\controller\WeChatBaseController;
use think\Session;
use think\Db;

class IndexController extends WeChatBaseController
{
    protected $user_id;

    public function _initialize()
    {
        parent::_initialize();
        $this->user_id = Session::get('user.id');
    }

    public function index()
    {
        if($this->isBank()){
            return $this->fetch("bank/index");
        };
        //商城URL
        $shop_url = Db::name('shopurl')->where('id',1)->value('shop_url');

        $this->assign('shop_url',$shop_url);
        return $this->fetch();
    }


}
