<?php

class Pushapi_MeiqiaController extends Core2_Controller_Action_Api
{
    /**
     * 初始化
     */
    public function init()
    {
        parent::init();
        
        $this->models['system_bug'] = new Model_SystemBug();
    }

    /**
     * 美恰服务器通知
     */
    public function callbackAction()
    {
        if (!form($this))
        {
            $this->json['errno'] = '1';
            $this->json['errmsg'] = $this->error->firstMessage();
            $this->_helper->json($this->json);
        }

        //获取用户id
        $meiqia = array();
 
        $meiqia = Zend_Json::decode($GLOBALS['HTTP_RAW_POST_DATA']);
        
        $userId = $meiqia['customizedData']['user_id'];
        
        //推送
        $push = new Core2_Jpush();
        $message= "您有一条客服信息";
        $title = "您有一条客服信息";
        $options['extras']=array('type'=>'meiqia','id'=>$meiqia['messageId']);
        $audience['member_id']=$userId;
        $a=$push->singlePush($audience,$message,$title,$options);

        $this->rows['system_bug'] = $this->models['system_bug']->createRow(array(
            'time' => time(),
            'errno' => "meiqia",
            'parameter' => json_encode($meiqia),
            'network' => $userId,
            'device' => $a,
        ));
        $this->rows['system_bug']->save();

    }
}