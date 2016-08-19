<?php

class Serviceapi_StaffController extends Core2_Controller_Action_Api  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		$this->models['service_staff'] = new Model_ServiceStaff();
	}
	
	/**
	 *  专属客服
	 */
	public function detailAction()
	{	 
		$staff = $this->_db->select()
			->from(array('m' => 'member'),'id')
			->joinLeft(array('s' => 'service_staff'),'m.service_staff_id = s.id',array('wx','phone','staff_name','avatar','introduce'))
			->where('m.id = ?',$this->_user->id)
			->where('s.status = 1')
			->query()
			->fetch();
		
		//客服存在
		if (!empty($staff['wx']))
		{
			unset($staff['id']);
		}
		//客服不存在
		else
		{
			$staff = $this->_db->select()
				->from(array('s' => 'service_staff'),array('wx','phone','staff_name','avatar','introduce'))
				->where('s.status = ?',1)
				->order('id ASC')
				->limit(1)
				->query()
				->fetch();
			//没有客服
			if (empty($staff['wx']))
			{
				$this->json['errno'] = '1';
				$this->json['errmsg'] = '没有专属客服';
				$this->_helper->json($this->json);
			}
		}			
		$this->json['staff'] = $staff;
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
}
?>