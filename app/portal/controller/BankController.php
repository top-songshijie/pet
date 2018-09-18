<?php
/*
 * 绑定功能
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018-09-17 上午 10:51
 */
namespace app\portal\controller;

use app\portal\model\PetsModel;
use app\portal\model\StrainModel;
use app\portal\model\UsersModel;
use cmf\controller\WeChatBaseController;
use think\Session;

class BankController extends WeChatBaseController
{
    protected $user_id;

    public function _initialize()
    {
        parent::_initialize();
        $this->user_id = Session::get('user.id');
    }

    public function index()
    {
        return $this->fetch();
    }

    /**
     * 绑定宠物
     */
    public function bankpet()
    {
        $type = $this->request->param('type','2','intval');
        $signPackage = $this->getSignPackage();
        $strain = new StrainModel();
        $data = $strain->field('id as value,content as label')->where('type',$type)->select()->toArray();

        $this->assign('signPackage',$signPackage);
        $this->assign('type',$type);
        $this->assign('data',json_encode($data));
        return $this->fetch();
    }

    /**
     * 绑定宠物ajax提交
     */
    public function bankpet_post()
    {
        $param = $this->request->param();
        $pets = new PetsModel();
        $pet_info = $pets->where([
            'user_id' => $this->user_id,
            'default' => 1
        ])->find();
        $param['user_id'] = $this->user_id;
        $param['create_time'] = date('Y-m-d H:i:s');
        if($pet_info){
            $param['default'] = 0;
        }else{
            $param['default'] = 1;
        }
        $res = $pets->insert($param);
        if($res){
            $this->apiResponse(1,'ok','');
        }
    }

    /**
     * 登录（绑定手机号）
     */
    public function login()
    {
        return $this->fetch();
    }

    /**
     * 登录操作
     */
    public function login_post()
    {
        $post = $this->request->param();
        $common = new CommonController();
        $user = new UsersModel();
        $res_validate = $common->validateMobileCode($post);
        if($res_validate){
            $res_mobile = $user->where('id',$this->user_id)->setField('mobile',$post['mobile']);
        }
        if($res_mobile){
            $this->apiResponse(1,'登录成功','');
        }
    }

}