<?php

class Regionapi_RegionController extends Core2_Controller_Action_Api  
{
	/**
	 *  数据接口
	 */
	public function dataAction()
	{
		$tree = array();
		$results = $this->_db->select()
			->from(array('r' => 'region'))
			->where('r.level = 1')
			->query()
			->fetchAll();
		foreach ($results as $r) 
		{
			$this->json['list']['province_list'][] = array('id' => $r['id'],'region_name' => $r['region_name']);
			
			$province = array(
				'id' => $r['id'],
				'region_name' => $r['region_name'],
				'cities' => array()
			);
			$cities = $this->_db->select()
				->from(array('r' => 'region'),array('id','region_name'))
				->where('r.parent_id = ?',$province['id'])
				->query()
				->fetchAll();
			foreach ($cities as $city) 
			{
				$city['counties'] = array();
				$counties = $this->_db->select()
					->from(array('r' => 'region'),array('id','region_name'))
					->where('r.parent_id = ?',$city['id'])
					->query()
					->fetchAll();
				foreach ($counties as $county) 
				{
					$city['counties'][] = $county;
				}
				$province['cities'][] = $city;
			}
			$tree[] = $province;
		}
		$this->json['tree'] = $tree;
		
		$cityList = array();
		$results = $this->_db->select()
			->from(array('r' => 'region'))
			->where('r.level = 2')
			->query()
			->fetchAll();
		foreach ($results as $r) 
		{
			$this->json['list']['city_list'][] = array('id' => $r['id'],'region_name' => $r['region_name']);
		}
		$this->json['tree']['city_list'] = $cityList;
		
		$results = $this->_db->select()
			->from(array('r' => 'region'))
			->where('r.level = 3')
			->query()
			->fetchAll();
		foreach ($results as $r) 
		{
			$this->json['list']['county_list'][] = array('id' => $r['id'],'region_name' => $r['region_name']);
		}
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
}

?>