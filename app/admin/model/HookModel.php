<?php
// +----------------------------------------------------------------------
// | bronet [ 以客户为中心 以奋斗者为本 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.bronet.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <bronet@126.com>
// +----------------------------------------------------------------------
namespace app\admin\model;

use think\Model;

class HookModel extends Model
{

    public function plugins()
    {
        return $this->belongsToMany('PluginModel', 'hook_plugin', 'plugin', 'hook');
    }

}