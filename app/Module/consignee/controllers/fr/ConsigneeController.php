<?php

class Consignee_ConsigneeController extends Core2_Controller_Action_Fr   
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['consignee'] = new Model_Consignee();
		$this->models['consignee2'] = new Model_Consignee();
	}
	
	/**
	 *  收货人列表
	 */
	public function listAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 获取全部收货人信息 */
		
		$results = $this->_db->select()
			->from(array('c' => 'consignee'))
			->where('c.member_id = ?',$this->_user->id)
			->where('c.status = ?','1')
			->order('c.default DESC')
			->query()
			->fetchAll();
		
		$consigneeList = array();
		foreach ($results as $r)
		{
			$consignee = array();
			$consignee['id'] = $r['id'];
			$consignee['consignee'] = $r['consignee'];
			$consignee['province_id'] = $r['province_id'];
			$consignee['city_id'] = $r['city_id'];
			$consignee['county_id'] = $r['county_id'];
			$consignee['address'] = $r['address'];
			$consignee['region_path'] = getRegionPath($r['city_id'],$r['county_id']);
			$consignee['mobile'] = $r['mobile'];
			$consignee['default'] = $r['default'];
			$consigneeList[] = $consignee;
		}
		$this->view->from_url = $this->input->from_url;
		$this->view->consignee_list = $consigneeList;
	}

	/**
	 *  添加收货人
	 */
	public function addAction()
	{	
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		$this->view->form_url = $this->input->from_url;
	}
	
	/**
	 *  添加收货人
	 */
	public function insertAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}

		$this->rows['consignee'] = $this->models['consignee']->createRow(array(
			'member_id' => $this->_user->id,
			'consignee' => $this->input->consignee,
			'province_id' => $this->input->province_id,
			'city_id' => $this->input->city_id,
			'county_id' => $this->input->county_id,
			'address' => $this->input->address,
			'zip' => $this->input->zip,
			'mobile' => $this->input->mobile,
			'default' => $this->input->default,
		));
		
		/* 如果是第一条收货人，自动设为默认收货人 */
		$count = $this->_db->select()
			->from(array('c' => 'consignee'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('c.member_id = ?',$this->_user->id)
			->where('c.status = ?',1)
			->query()
			->fetchColumn();
		if ($count == 0) 
		{
			$this->rows['consignee']->default = 1;
		}else{
			if($this->input->default==1){
				//修改默认地址
				$set = array (
					'default' => 0,
				);
				$db = Zend_Registry::get('db');
				$table = 'consignee';
				$where = $db->quoteInto('member_id = ?',$this->_user->id);
			    $rows_affected = $db->update($table, $set, $where);	
			}
		
		}
		
		$id = $this->rows['consignee']->save();
		
		$this->json['errno'] = '0';
		if($this->input->form_url){
			$gourl = resetUrl($this->input->form_url,'consignee_id').'&consignee_id='.$id;
			$this->json['gourl'] = $gourl;
		}else{
			$gourl = DOMAIN.'consignee/consignee/list';
			$this->json['gourl'] = $gourl;		
		}
		$this->json['notice'] = '添加成功';
		$this->_helper->json($this->json);
	}

	
	/**
	 *  编辑收货人
	 */
	public function editAction()
	{	
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		$consignee = $this->_db->select()
			->from(array('c' => 'consignee'))
			->where('c.member_id = ?',$this->_user->id)
			->where('c.id = ?',$this->input->id)
			->query()
			->fetch();
		$this->view->consignee = $consignee;
		$this->view->form_url = resetUrl($this->input->from_url,'consignee_id');
	}


	/**
	 *  编辑收货人
	 */
	public function updateAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$this->rows['consignee'] = $this->models['consignee']->find($this->input->id)->current();
		$this->rows['consignee']->consignee = $this->input->consignee;
		$this->rows['consignee']->province_id = $this->input->province_id;
		$this->rows['consignee']->city_id = $this->input->city_id;
		$this->rows['consignee']->county_id = $this->input->county_id;
		$this->rows['consignee']->address = $this->input->address;
		$this->rows['consignee']->zip = $this->input->zip;
		$this->rows['consignee']->mobile = $this->input->mobile;
		$this->rows['consignee']->telephone = $this->input->telephone;
		$this->rows['consignee']->save();
		
		$this->json['errno'] = '0';
		if($this->input->form_url){
			$gourl = resetUrl($this->input->form_url,'consignee_id').'&consignee_id='.$this->input->id;
			$this->json['gourl'] = $gourl;
		}
		$this->json['notice'] = '编辑成功';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  设为默认收货人
	 */
	public function setdefaultAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$this->_db->update('consignee',array('default' => 0),array('member_id = ?' => $this->_user->id));
		
		$this->rows['consignee'] = $this->models['consignee']->find($this->input->id)->current();
		$this->rows['consignee']->default = 1;
		$this->rows['consignee']->save();
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  删除
	 */
	public function deleteAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$this->rows['consignee'] = $this->models['consignee']->find($this->input->id)->current();
		$this->rows['consignee']->status = -1;
		$this->rows['consignee']->save();
		
		/* 如果是默认收货人则重新设置 */
		
		if ($this->rows['consignee']->default == 1) 
		{
			$id = $this->_db->select()
				->from(array('c' => 'consignee'),array('id'))
				->where('c.member_id = ?',$this->_user->id)
				->order('c.dateline DESC')
				->query()
				->fetchColumn();
			if (!empty($id)) 
			{
				$this->_db->update('consignee',array('default' => 1),array('id = ?' => $id));
			}
		}
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
}

?>