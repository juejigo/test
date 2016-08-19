<?php

class Search_ProductController extends Core2_Controller_Action_Fr  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['search_count'] = new Model_SearchCount();
	}
	
	/**
	 *  搜索
	 */
	public function indexAction()
	{
		if (!form($this)) 
		{
			$this->_helper->notice($this->error->firstMessage(),'','error_1',array(
				array(
					'href' => '/product/product/list',
					'text' => '商品列表'),
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回上一面'),
			));
		}
		
		$engine = $this->_config->search->engine;
		
		if ($engine == 'sphinx')    // sphinx
		{
			/* 初始化 sphinx */
			
			require_once('api/sphinx/sphinxapi.php');
			
			$sphinxClient = new SphinxClient();
			$sphinxClient->SetServer($this->_conf->search->sphnix->host,$this->_conf->search->sphnix->port);
			$sphinxClient->SetMatchMode($this->_conf->search->sphnix->matchMode);
			$sphinxClient->SetRankingMode($this->_conf->search->sphnix->rankingMode);
			$sphinxClient->SetArrayResult(true);
			
			/* 区 */
			
			if (!empty($this->input->area)) 
			{
				$sphinxClient->SetFilter('area',array($this->input->area));
			}
			
			/* 分类 */
			
			if (!empty($this->input->cate_id)) 
			{
				$sphinxClient->SetFilter('cate_id',array($this->input->cate_id));
			}
			
			/* 排序 */
			
			switch ($this->input->sort)
			{
				case 'sells':
					$sphinxClient->SetSortMode(SPH_SORT_ATTR_DESC,'sales');
					break;
				case 'dateline':
					$sphinxClient->SetSortMode(SPH_SORT_ATTR_DESC,'dateline');
					break;
				case 'pricea':
					$sphinxClient->SetSortMode(SPH_SORT_ATTR_ASC,'price');
					break;
				case 'priced':
					$sphinxClient->SetSortMode(SPH_SORT_ATTR_DESC,'price');
					break;
				default:
					$sphinxClient->SetSortMode(SPH_SORT_ATTR_DESC,'dateline');
					break;
			}
			
			$sphinxClient->SetLimits($this->input->perpage * ($this->input->page - 1),intval($this->input->perpage));
			$result = $sphinxClient->Query("{$this->input->q}",'productMainIndex');
			
			$ids = array();
			$results = array();
			if (!empty($result['matches'])) 
			{
				foreach ($result['matches'] as $match) 
				{
					$ids[] = $match['id'];
				}
				
				$results = $this->_db->select()
					->from(array('p' => 'product'))
					->where('p.id IN (?)',$ids)
					->query()
					->fetchAll();
			}
		}
		else if ($engine == 'mysql')    // mysql
		{
			$select = $this->_db->select()
				->from(array('p' => 'product'),array(new Zend_Db_Expr('COUNT(*)')))
				->where("p.keywords LIKE '%{$this->input->q}%' or p.product_name LIKE '%{$this->input->q}%'")
				->where('p.status = ?',2);
				
			/* 区 */
		
			if (!empty($this->input->area)) 
			{
				$select->where('p.area = ?',$this->input->area);
			}
				
			/* 分类 */
			
			if (!empty($this->input->cate_id)) 
			{
				$select->where('p.cate_id = ?',$this->input->cate_id);
			}
			
			/* 排序 */
			
			switch ($this->input->sort)
			{
				case 'sells':
					$select->order('p.sells DESC');
					break;
				case 'dateline':
					$select->order('p.dateline DESC');
					break;
				case 'priced':
					$select->order('p.price DESC');
					break;
				case 'pricea':
					$select->order('p.price ASC');
					break;
				default:
					$select->order('p.dateline DESC');
					break;
			}
			
			$results = $select
				->reset(Zend_Db_Select::COLUMNS)
				->columns('*','p')
				->limitPage($this->input->page,$this->input->perpage)
				->query()
				->fetchAll();
		}
		
		/* 记录搜索关键字 */
		
		$count = $this->_db->select()
			->from(array('c' => 'search_count'))
			->where('c.words = ?',$this->input->q)
			->query()
			->fetch();
		if (!empty($count)) 
		{
			$this->models['search_count']->update(
				array(
					'count' => new Zend_Db_Expr('count + 1'),
					'last_user' => $this->_auth->hasIdentity() ? $this->_user->id : 0),
				array('id = ?' => $count['id'])
			);
		}
		else 
		{
			$this->_rows['search_count'] = $this->models['search_count']->createRow(array(
				'search_item' => 1,
				'words' => $this->input->q,
				'last_user' => $this->_auth->hasIdentity() ? $this->_user->id : 0)
			)->save();
		}
		
		/* 处理数据 */
		
		$productList = array();
		foreach ($results as $result) 
		{
			$product = array();
			$product['id'] = $result['id'];
			$product['product_name'] = $result['product_name'];
			$product['image'] = thumbpath($result['image'],220);
			$product['price'] = $result['price_range'];
			$product['mktprice'] = $result['mktprice'];
			$product['sells'] = $result['sells'];
			$productList[] = $product;
		}
		$this->json['product_list'] = $productList;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  top10
	 */
	public function top10Action()
	{
		/* 搜索 TOP10 */
		$top10 = array();
		$this->view->tops = $top10;
	}

	/**
	 *  最近搜索
	 */
	public function recentAction()
	{

		$search_wds = Core_Cookie::get('search_wds');
		if($search_wds){
			$search_wds = explode(',',$search_wds);
		}
		$this->view->search_wds = $search_wds;
	}

}

?>