<?php
/*
 * 通用类
 * By: Phpstorm
 * Author: XiaoJie
 * Datetime: 2018/8/16 4:18
 */

namespace app\portal\controller;

use cmf\controller\WeChatBaseController;
use app\portal\model\MobileCodeModel;
use think\Config;
use think\Db;

/**
 * @title 通用文件
 * @description 接口说明
 */

class CommonController extends WeChatBaseController
{
    /**
     * 上传照片
     */
    public function uploadPic()
    {
        $file = request()->file('pic');
        if(empty($file)){
            $this->apiResponse(0,'上传文件不能为空','');
        }
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload' . DS . 'header');
            if($info){
                $baseurl = $info->getSaveName();
                $this->apiResponse(1,'上传成功',$baseurl);
            }else{
                $error = $file->getError();
                $this->apiResponse(0,$error,'');
            }
        }
    }

    /**
     * 发送手机验证码
     */
    public function sendMobileCode()
    {
        Db::startTrans();
        $mobile = $this->request->param('mobile','');
        $mc = new MobileCodeModel();
        $mobile_code = rand(100000, 999999);
        $search = '/^0?1[3|4|5|6|7|8][0-9]\d{8}$/';
        if (!preg_match($search,$mobile)) {
            $this->apiResponse(0,'手机号格式有误','');
        }
        if(empty($mobile)){
            $this->apiResponse(0,'缺少必要参数:MOBILE','');
        }
        $content = "【宠物预约】您的验证码为".$mobile_code."，如非本人操作请忽略本短信!";
        $info = $mc->where('mobile',$mobile)->find();
        if($info){
            if($info['expire_time'] > time() and $info['is_use'] == 0){
                $this->apiResponse(0,'不能频繁发送验证码','');
            }
            $res = $mc->where('id',$info['id'])->data([
                'mobile' => $mobile,
                'mobile_code' => $mobile_code,
                'is_use' => 0,
                'expire_time' => time()+300,
                'count' => $info['count'] +1
            ])->update();
        }else{
            $res = $mc->insert([
                'mobile' => $mobile,
                'mobile_code' => $mobile_code,
                'is_use' => 0,
                'expire_time' => time()+300,
                'count' => 1
            ]);
        }
        if($res){
            //发送验证码
            if(substr($this->sendCode($mobile,$content),0,1) != 1){
                Db::rollback();
                $this->apiResponse(0,'短信发送失败','');
            }
            Db::commit();
            $this->apiResponse(1,'发送成功','');
        }
    }

    /**
     * 验证手机验证码
     */
    public function validateMobileCode($post)
    {
        $mc = new MobileCodeModel();
        if(empty($post['mobile'])){
            $this->apiResponse(0,'缺少必要参数：MOBILE','');
        }
        if(empty($post['mobile_code'])){
            $this->apiResponse(0,'缺少必要参数：MOBILE_CODE','');
        }
        $res_find = $mc->where([
            'mobile' => $post['mobile'],
            'mobile_code' => $post['mobile_code'],
            'is_use' => 0,
            'expire_time' => ['gt',time()]
        ])->find();
        if($res_find){
            $res_update = $mc->where('id',$res_find['id'])->setField('is_use',1);
            if($res_update){
                return true;
            }
        }else{
            $this->apiResponse(0,'验证未通过','');
        }
    }

    /**
     * 发送手机验证码
     * @param $mobile
     * @param $code
     * @return mixed
     */
    private function sendCode($mobile,$content){
        date_default_timezone_set('PRC');//设置时区
        $url 		= "http://www.ztsms.cn/sendNSms.do";//提交地址
        $username 	= Config::get('MB_USERNAME');//用户名
        $password 	= Config::get('MB_PASSWORD');//原密码
        $data = array(
            'content' 	=> $content,//短信内容
            'mobile' 	=> $mobile,//手机号码
            'productid' => '676767',//产品id
            'xh'		=> ''//小号
        );
        $isTranscoding = false;
        $data['content'] = $isTranscoding === true ? mb_convert_encoding($data['content'], "UTF-8") : $data['content'];
        $data['username']=$username;
        $data['tkey'] 	= date('YmdHis');
        $data['password'] = md5(md5($password) . $data['tkey']);
        $curl = curl_init();// 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_POST, true); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS,  http_build_query($data)); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, false); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // 获取的信息以文件流的形式返回
        $result = curl_exec($curl); // 执行操作
        return $result;
    }

    /**
     * 微信上传图片
     */
    public function upload_wx_pic(){
        $access_token = $this->getAccessToken();
        $img_str = $this->request->param('media','','string');
        $img_arr = explode(',',rtrim($img_str,','));
        $foldername = 'petavatar/'.date('Ymd');
        $imgurls = '';
        foreach($img_arr as $v) {
            $url = $this->getmedia($access_token,$v,$foldername);
            $imgurls .=  ','. $url;
        }
        $data['imgurls'] = ltrim($imgurls,',');
        $data['httpimgurls'] = cmf_get_image_preview_url($data['imgurls']);

        $this->apiResponse(1,'上传成功',$data);
    }

    /**
     * 根据media_id下载微信图片
     * @param $access_token
     * @param $media_id
     * @param $foldername
     * @return string
     */
    private function getmedia($access_token,$media_id,$foldername){
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;
        if (!file_exists("./upload/".$foldername)) {
            mkdir("./upload/".$foldername, 0777, true);
        }
        $file_name=date('YmdHis').rand(1000,9999).'.jpg';
        $targetName = './upload/'.$foldername.'/'.$file_name;
        $saveName = $foldername.'/'.$file_name;
        $ch = curl_init($url); // 初始化
        $fp = fopen($targetName, 'wb'); // 打开写入
        curl_setopt($ch, CURLOPT_FILE, $fp); // 设置输出文件的位置，值是一个资源类型
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        return $saveName;
    }

}