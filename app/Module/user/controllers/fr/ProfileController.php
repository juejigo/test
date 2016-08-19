<?php

class User_ProfileController extends Core2_Controller_Action_Fr 
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['member_profile'] = new Model_MemberProfile();
		$this->models['member'] = new Model_Member();
		$this->models['mail'] = new Model_Mail();
	}

	/**
	 *  资料修改
	 */
	public function editAction()
	{
		if ($this->_request->isPost()) 
		{
			
			if (!form($this)) 
			{
				$this->_helper->notice($this->error->firstMessage(),'','error_1',array(
					array(
						'href' => '/',
						'text' => '返回首页'),
					array(
						'href' => 'javascript:history.go(-1);',
						'text' => '返回上一面'),
				));
			}
			if($_FILES['image']['name'])
			{
				$image = new Core2_Image('avatar');
				if (!$ret = $image->upload('image'))
				{
					$this->_helper->notice('图片格式错误或图片过大','','error_1',array(
						array(
							'href' => '/',
							'text' => '返回首页'),
						array(
							'href' => 'javascript:history.go(-1);',
							'text' => '返回上一面'),
					));
				}			
			}
			$this->rows['member_profile'] = $this->models['member_profile']->find($this->_user->id)->current();
			$this->rows['member_profile']->member_name = $this->input->member_name;
			$this->rows['member_profile']->sex = $this->input->sex;
			if($ret['url'])$this->rows['member_profile']->avatar = $ret['url'];
			$result = $this->rows['member_profile']->save();
			 echo "<script language=JavaScript> location.replace(location.href);</script>";
		}

		if ($this->_request->isGet()) 
		{
			$profile = $this->_db->select()
				->from(array('p' => 'member_profile'),array('avatar','member_name','alias','sex'))
				->where('p.member_id = ?',$this->_user->id)
				->query()
				->fetch();
			$profile['group_name'] = getGroupName($this->_user->role,$this->_user->group);
			$this->view->ages = $ages;
			$this->view->profile = $profile;
		}
	}

	
	/**
	 *  修改头像
	 */
	public function avatarAction()
	{
	    /* 取消视图 */
            $this->_helper->viewRenderer->setNoRender();
            
            /* 初始化变量 */
            $this->_vars['json'] = array();
            $this->_vars['image'] = array();
            
            $image = new Core2_Image('avatar');
            if (!$this->_vars['image'] = $image->upload('image'))
            {
                $this->_vars['json']['flag'] = 'error';
                $this->_vars['json']['msg'] = '图片格式错误或图片过大';
                $this->_helper->json($this->_vars['json']);
            }
            $this->rows['member_profile'] = $this->models['member_profile']->find($this->_user->id)->current();
            $this->rows['member_profile']->avatar = $this->_vars['image']['url'];
            $this->rows['member_profile']->save();
            
            $this->_vars['json']['errno'] = '0';
            $this->_vars['json']['img'] = $this->_vars['image']['url'];
            $this->_helper->json($this->_vars['json']);
	}
	
	/**
	 *  修改资料
	 * 
	 */
	public function profileAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = 1;
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$this->rows['member_profile'] = $this->models['member_profile']->find($this->_user->id)->current();
		$this->rows['member_profile']->member_name = $this->input->member_name;
		$this->rows['member_profile']->sex = $this->input->sex;
		$this->rows['member_profile']->province_id = $this->input->province_id;
		$this->rows['member_profile']->city_id = $this->input->city_id;
		$this->rows['member_profile']->county_id = $this->input->county_id;
		$this->rows['member_profile']->address = $this->input->address;
		$this->rows['member_profile']->save();
		
		$this->json['errno'] = 0;
		$this->_helper->json($this->json);
	}

	public function changepwAction()
	{
	}
	
	/**
	 *  修改密码
	 */
	public function passwordAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = 1;
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}

		/* 更新密码 */
		$this->rows['member'] = $this->models['member']->find($this->_user->id)->current();
		$this->rows['member']->password = $this->input->password;
		$this->rows['member']->save();
		
		$this->json['errno'] = 0;
		$this->json['notice'] = '密码修改成功';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  重置密码
	 */
	public function resetpasswordAction() 
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 权限 */
		
		$count = $this->_db->select()
			->from(array('r' => 'app_smscode'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('r.mobile = ?',$this->_user->account)
			->query()
			->fetchColumn();
		
		if ($count == 0) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = '重置失败';
			$this->_helper->json($this->json);
		}

		/* 更新密码 */
		
		$this->rows['member'] = $this->models['member']->find($this->_user->id)->current();
		$this->rows['member']->password = $this->input->password;
		$this->rows['member']->save();
		
		$this->json['errno'] = 0;
		$this->_helper->json($this->json);
	}
	
	/**
	 *  修改手机
	 */
	public function mobileAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = 1;
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$this->rows['member'] = $this->models['member']->find($this->_user->id)->current();
		$this->rows['member']->account = $this->input->mobile;
		$this->rows['member']->save();
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  绑定邮箱
	 */
	public function emailAction()
	{
		if ($this->_request->isGet()) 
		{
			/* 获取绑定邮箱 */
			
			$email = $this->_db->select()
				->from(array('m' => 'member'),array('email'))
				->where('m.id = ?',$this->_user->id)
				->query()
				->fetchColumn();
			
			$this->json['errno'] = 0;
			$this->json['email'] = $email;
			$this->_helper->json($this->json);
		}
		
		if ($this->_request->isPost()) 
		{
			if (!form($this)) 
			{
				$this->json['errno'] = 1;
				$this->json['errmsg'] = $this->error->firstMessage();
				$this->_helper->json($this->json);
			}
			
			/* 发送邮件 */
			
			$this->rows['mail'] = $this->models['mail']->createRow(array(
				'member_id' => $this->_user->id,
				'email' => $this->input->email
			));
			$this->rows['mail']->save();
			
			$mail = new Core_Mail($this->_config->mail->transport);
			$title = SITE_NAME . '绑定邮箱';
			$hash = md5(SCRIPT_TIME);
			$content = "您正在进行绑定邮箱操作，点击下面链接，即可完成绑定。<br /><a href=\"" . DOMAIN . "utility/email/auth/id/{$this->rows['mail']->id}/hash/{$hash}\">" . DOMAIN . "utility/email/auth/id/{$this->rows['mail']->id}/hash/{$hash}</a>";
			$mail->send(array('email' => $this->input->email,'name' => $this->input->email),$title,$content);
			
			$this->json['errno'] = 0;
			$this->_helper->json($this->json);
		}
	}
}

?>