<?php
class Systemcp_SettingController extends Core2_Controller_Action_Cp
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 *  首页
	 */
	public function indexAction()
	{
		$this->_redirect('/systemcp/setting/config');
	}
	
	/**
	 *  物流运费
	 */
	public function shippingtplAction()
	{
		if ($this->_request->isPost())
		{
			if (form($this))
			{
				if (!empty($this->input->province_id))
				{
					$fee = $this->input->fee;
					$province_id = $this->input->province_id;
					$sql = 'INSERT INTO `system_shippingtpl` (`province_id`,`fee`) VALUES';
					foreach ($this->input->status as $key => $status)
					{
						//删除
						if($status == '-1')
						{
							$this->_db->delete('system_shippingtpl',array('province_id = ?' => $province_id[$key]));
						}
						//写入
						else
						{
							$sql .= "('".$province_id[$key]."','".$fee[$key]."'),";
							$num = 1;
						}
					}
					$sql = rtrim($sql,',');
					//已存在就更新
					$sql .= 'ON DUPLICATE KEY UPDATE fee = VALUES(fee)';
					//有条目
					if(isset($num))
					{
						$db = Zend_Registry::get('db');
						$db->query($sql);
					}	
				}
				$this->_helper->notice('保存成功','','success',array(
					array(
						'href' => '/systemcp/setting/shippingtpl',
						'text' => '返回')
				));
			}	
		}
		//省份
		$regions = $this->_db->select()
			->from(array('r' => 'region'),array('id','region_name'))
			->where('level = ?',1)
			->query()
			->fetchAll();
		
		//物流
		$shipping = $this->_db->select()
			->from(array('s' => 'system_shippingtpl'))
			->query()
			->fetchAll();
		
		$this->view->regions = $regions;
		$this->view->shipping = $shipping;
	}

	/**
	 *  基本设置
	 */
	public function configAction()
	{
		if ($this->_request->isPost())
		{
			if (form($this))
			{
				if (!empty($this->input->id))
				{
					$ids = implode(',', array_keys($this->input->id));
					$sql = "UPDATE `system_config` SET `value` = CASE `id` ";
					foreach ($this->input->id as $id => $value)
					{
						$sql .= sprintf("WHEN %d THEN '%s' ", $id, $value);
					}
					$sql .= "END WHERE `id` IN ($ids) and `allow_modify` = 1";
					$db = Zend_Registry::get('db');
					$db->query($sql);
				}
				
				$this->_helper->notice('保存成功','','success',array(
					array(
						'href' => '/systemcp/setting/config',
						'text' => '返回')
				));
			}
		}
		//基本设置
		$config = $this->_db->select()
			->from(array('c' => 'system_config'))
			->query()
			->fetchAll();
 		$arr = treelist($config,0,'id','parent_id');
 		foreach ($arr as $key => $ar)
 		{
 			if ($ar['input_type'] == 3)
 			{
 				$arr[$key]['options'] = unserialize($ar['options']);
 			}
 		}
 		$this->view->config = $arr;
	}
}
?>