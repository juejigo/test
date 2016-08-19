<?php

class Scrathcp_ScrathController extends Core2_Controller_Action_Cp
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['scrath'] = new Model_Scrath();
		$this->models['scrath_product'] = new Model_ScrathProduct();
	}
	
	/**
	 *  首页
	 */
	public function indexAction()
	{
		$this->_redirect('/scrathcp/scrath/list');	}
	
	/**
	 *  列表
	 */
	public function listAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
					array(
							'href' => '/admincp',
							'text' => '返回')
			));
		}

		/* 构造 SQL 选择器 */
		
		$perpage = 10;
		$select = $this->_db->select()
			->from(array('s' => 'scrath'),array(new Zend_Db_Expr('COUNT(*)')));
		
		$query = '/scrathcp/scrath/list?page={page}';
		
		if (!empty($this->paramInput->scrath_id))
		{
			$select->where('s.id = ?',$this->paramInput->scrath_id);
			$query .= "&id={$this->paramInput->scrath_id}";
		}
		
		if (!empty($this->paramInput->scrath_name))
		{
			$select->where('s.scrath_name like ?','%'.$this->paramInput->scrath_name.'%');
			$query .= "&scrath_name={$this->paramInput->scrath_name}";
		}
		
		if (!empty($this->paramInput->dateline_from))
		{
			$select->where('s.start_time >= ?',strtotime($this->paramInput->dateline_from));
			$query .= "&dateline_from={$this->paramInput->dateline_from}";
		}
		 
		if (!empty($this->paramInput->dateline_to))
		{
			$select->where('s.end_time <= ?',strtotime($this->paramInput->dateline_to));
			$query .= "&dateline_to={$this->paramInput->dateline_to}";
		}
		
		if ($this->paramInput->status !== '')
		{    
			$select->where('s.status = ?',$this->paramInput->status);
			$query .= "&status={$this->paramInput->status}";
			$this->view->status = $this->paramInput->status;
		}
		else
		{
			$select->where('s.status in (?)',array(0,1,2));
			$query .= "&status=";
		}
		
		/* 分页 */

		$count = $select->query()->fetchColumn();   
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
		$this->view->count = $count;
		
		/* 列表 */
		
		$scrathList = array();
		$scrathListResult = $select->reset(Zend_Db_Select::COLUMNS)
			->columns(array('id','scrath_name','start_time','end_time','status','lottery_num'),'s')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		
		if (!empty($scrathListResult))
		{
			foreach ($scrathListResult as $key => $value)
			{
				$scrathList[$key]['id'] = $value['id'];
				$scrathList[$key]['scrath_name'] = $value['scrath_name'];
				$scrathList[$key]['start_time'] = Date("Y-m-d H:i",$value['start_time']);
				$scrathList[$key]['end_time'] = Date("Y-m-d H:i",$value['end_time']);
				$scrathList[$key]['status'] = $value['status'];
				$scrathList[$key]['lottery_num'] = $value['lottery_num'];
			}
		}
		$this->view->scrathList = $scrathList;
		$this->view->status = $this->paramInput->status;
	}
	
	
	/**
	 *  添加
	 */
	public function addAction()
	{
		/* 检验传值 */
	
		if (!params($this))
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
					array(
							'href' => '/admincp',
							'text' => '返回')
			));
		}
	
		if ($this->_request->isPost())
		{
			if (form($this))
			{
				/* 插入活动 */
				$this->rows['scrath'] = $this->models['scrath']->createRow(array(
						'scrath_name' => $this->input->scrath_name,
 						'image' => $this->input->info_image,
						'start_time' => strtotime($this->input->start_time),
						'end_time' => strtotime($this->input->end_time),
 						'content' => $this->input->getUnescaped('content'),
				        'info' => $this->input->info,
						'draw_amount' => $this->input->draw_amount,
						'total_num' => $this->input->total_num,
				        'lottery_num' => $this->input->lottery_num,
						//row里设置
						'status'=> '0'
				));
				
				//插入的id
				$lastId = $this->rows['scrath']->save();

				if (!empty($this->input->product_name))
				{	
					/* 插入奖品*/
					
					$level = $this->input->product_level;
					$image = $this->input->image;
					$stock = $this->input->stock;
					$sql = 'INSERT INTO `scrath_product` (`scrath_id`,`product_name`,`product_level`,`stock`,`image`,`total_num`,`status`) VALUES';
					foreach ($this->input->product_name as $key => $value)
					{
						//拼接sql
						$sql .= "('".$lastId."','".$value."','".$level[$key]."','".$stock[$key]."','".$image[$key]."','".$stock[$key]."',1),";
					}
					$sql = rtrim($sql,',');
					$db = Zend_Registry::get('db');
					$db->query($sql);
				}
				$this->_helper->notice('添加成功','','success',array(
						array(
								'href' => '/scrathcp/scrath/list',
								'text' => '返回')
				));
			}
		}
	}
	
	
	/**
	 *  修改
	 */
	public function editAction()
	{
		/* 检验传值 */
	
		if (!params($this))
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
					array(
							'href' => '/admincp',
							'text' => '返回')
			));
		}
	
		if ($this->_request->isPost())
		{
			if (form($this))
			{
				$this->rows['scrath'] = $this->models['scrath']->find($this->paramInput->id)->current();
				
				/* 修改活动 */
				
				$this->rows['scrath']->scrath_name = $this->input->scrath_name;
				$this->rows['scrath']->start_time = strtotime($this->input->start_time);
				$this->rows['scrath']->end_time = strtotime($this->input->end_time);
 				$this->rows['scrath']->content = $this->input->getUnescaped('content');
 				$this->rows['scrath']->info = $this->input->info;
				$this->rows['scrath']->draw_amount = $this->input->draw_amount;
				$this->rows['scrath']->total_num = $this->input->total_num;
				$this->rows['scrath']->image = $this->input->info_image;
				$this->rows['scrath']->lottery_num = $this->input->lottery_num;
				$this->rows['scrath']->save();
				
				
				//修改的id
				$lastId = $this->paramInput->id;
				if (!empty($this->input->product_name))
				{
					/* 插入新的奖品*/
					$ids = $this->input->product_id;
					$status = $this->input->status;
					$level = $this->input->product_level;
					$image = $this->input->image;
					$surplus = $this->input->surplus;
					$stock = $this->input->stock;
					$sql = 'INSERT INTO `scrath_product` (`id`,`scrath_id`,`product_name`,`product_level`,`stock`,`image`,`total_num`,`status`) VALUES';
					foreach ($this->input->product_name as $key => $value)
					{
						if (empty($status[$key]))
						{
							$ids[$key] = '';
							$status[$key] = 1;
						}
						//拼接sql
						$sql .= "('".$ids[$key]."','".$lastId."','".$value."','".$level[$key]."','".$surplus[$key]."','".$image[$key]."','".$stock[$key]."','".$status[$key]."'),";
					}
					$sql = rtrim($sql,',');
					//已存在就更新
					$sql.='ON DUPLICATE KEY UPDATE product_name = VALUES(product_name),product_level = VALUES(product_level),stock = VALUES(stock),image = VALUES(image),total_num = VALUES(total_num),status = VALUES(status)';
					
					$db = Zend_Registry::get('db');
					$db->query($sql);
				}
				$this->_helper->notice('修改成功','','success',array(
						array(
								'href' => '/scrathcp/scrath/list',
								'text' => '返回')
				));
			}
		}
		
		/* 活动信息*/
		
		$scrathInfoResult = $this->_db->select()
			->from(array('s' => 'scrath'),array('scrath_name','start_time','end_time','draw_amount','total_num','lottery_num','content','info','image as info_image'))
			->joinLeft(array('p' => 'scrath_product'), 's.id = p.scrath_id',array('id','scrath_id','product_name','product_level','stock','total_num as p.total_num','image','status'))
			->where('s.id = ?',$this->paramInput->id)
			->where('p.status = ?',1)
			->query()
			->fetchAll();
		$scrathInfo = $scrathInfoResult[0];
		$scrathInfo['start_time'] = date('Y-m-d H:i',$scrathInfo['start_time']);
		$scrathInfo['end_time'] = date('Y-m-d H:i',$scrathInfo['end_time']);
		foreach ($scrathInfoResult as $key => $value)
		{
			$scrathProductList[$key]['id'] = $value['id'];
			$scrathProductList[$key]['scrath_id'] = $value['scrath_id'];
			$scrathProductList[$key]['product_name'] = $value['product_name'];
			$scrathProductList[$key]['product_level'] = $value['product_level'];
			$scrathProductList[$key]['total_num'] = $value['p.total_num'];
			$scrathProductList[$key]['stock'] = $value['stock'];
			$scrathProductList[$key]['image'] = $value['image'];
			$scrathProductList[$key]['status'] = $value['status'];
		}
		$this->view->scrathInfo = $scrathInfo;
		$this->view->scrathProductList = $scrathProductList;		
	}
	
	/**
	 *  ajax
	 */
	public function  ajaxAction()
	{
		if (!$this->_request->isXmlHttpRequest())
		{
			exit;
		}
	
		$op = $this->_request->getQuery('op','');
		$json = array();
		$this->_helper->viewRenderer->setNoRender();
	
		/* 检验传值 */
		
		if (!ajax($this))
		{
	   		$json['errno'] = 1;
	   		$json['errmsg'] = $this->error->firstMessage();
	   		$this->_helper->json($json);
		}
		
		switch ($op)
		{
			/* 上架*/
			
			case 'up':
				
				if (!empty($this->input->id))
				{
					$this->rows['scrath'] = $this->models['scrath']->find($this->input->id)->current();
					$this->rows['scrath']->status = 1;
					$this->rows['scrath']->save();
					$json['errno'] = 0;
					$this->_helper->json($json);
				}
				break;
				
			/* 下架*/
			
			case 'down':
				
				if (!empty($this->input->id))
				{
					$this->rows['scrath'] = $this->models['scrath']->find($this->input->id)->current();
					$this->rows['scrath']->status = 0;
					$this->rows['scrath']->save();
					$json['errno'] = 0;
					$this->_helper->json($json);
				}
				break;
				
			/* 删除*/
			
			case 'delete':	
				
				//批量
				if (is_array($this->input->id))
				{
					foreach ($this->input->id as $key => $value)
					{
						$idlist[$value] = -1;
					}
					$ids = implode(',', array_keys($idlist));
					$sql = "UPDATE scrath SET status = CASE id ";
					foreach ($idlist as $id => $status)
					{
						$sql .= sprintf("WHEN %d THEN %d ", $id, $status);
					}
					$sql .= "END WHERE id IN ($ids)";
					$db = Zend_Registry::get('db');
					$db->query($sql);
					$json['errno'] = 0;
					$this->_helper->json($json);
				}
				//单条
				elseif (!empty($this->input->id))
				{
					$this->rows['scrath'] = $this->models['scrath']->find($this->input->id)->current();
					$this->rows['scrath']->status = -1;
					$this->rows['scrath']->save();
					$json['errno'] = 0;
					$this->_helper->json($json);
				}				
				break;
				
			default:
				break;
		}
	}
	
	/**
	 *  上传图片
	 */
	public function imageAction()
	{
		$json = array();
		$this->_helper->viewRenderer->setNoRender();
		$image = new Core2_Image('avatar');
	
		if (!$ret = $image->upload('upImg'))
		{
			$json['errno'] = 1;
			$json['errmsg'] = '图片格式错误或图片过大';
			$this->_helper->json($json);
		}
	
		$json['errno'] = 0;
		$json['img'] = $ret['url'];
		$this->_helper->json($json);
	}
}

?>