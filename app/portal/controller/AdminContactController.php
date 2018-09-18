<?php
/*
 * 联系的电话和二维码
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018-09-14 下午 5:37
 */
namespace app\portal\controller;

use app\portal\model\ContactModel;
use cmf\controller\AdminBaseController;

class AdminContactController extends AdminBaseController
{
    public function index()
    {
        $contact = new ContactModel();
        if($this->request->isPost()){
            $param = $this->request->param();
            $param['id'] = 1;
            $param['update_time'] = date('Y-m-d H:i:s');
            $res = $contact->update($param);
            if($res){
                $this->success('更新成功');
            }
        }else{
            $info = $contact->find(1);

            $this->assign('info',$info);
            return $this->fetch();
        }
    }

}