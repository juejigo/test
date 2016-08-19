<?php

class Productcp_TagController extends Core2_Controller_Action_Cp 
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['product_tag'] = new Model_ProductTag();
		$this->models['product_tagdata'] = new Model_ProductTagdata();
	}
	
	/**
	 *  首页
	 */
	public function indexAction() 
	{
		$this->_redirect('/productcp/tag/list');
	}
	
	/**
	 *  列表
	 */
	public function listAction()
	{
		/* 检验传值 */
	    
		if (!params($this)) 
		{
			/* 提示 */
		    
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/productcp/tag',
					'text' => '返回')
			));
		}
		
		/* 构造 SQL 选择器 */
		
		$perpage = 20;
		$select = $this->_db->select()
			->from(array('t' => 'product_tag'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('t.status = ?',1);
		
		/* 分页 */
		
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,"/productcp/tag/list?page={page}");
		$this->view->pagebar = $corepage->output();
		
		/* 列表 */
		
		$tagList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','t')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		$this->view->tagList = $tagList;
	}
	
	/**
	 *  tag列表
	 */
	
	public function taglistAction()
	{
		/* 检验传值 */
	    
		if (!params($this)) 
		{
			/* 提示 */
		    
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/productcp/tag/list',
					'text' => '返回')
			));
		}
		/* 构造 SQL 选择器 */
		
		$perpage = 20;
		$select = $this->_db->select()
			->from(array('t' => 'product_tagdata'),array(new Zend_Db_Expr('COUNT(*)')))
			->joinLeft(array('p' => 'product'), 't.product_id = p.id')
			->joinLeft(array('a' => 'product_tag'), 't.tag_id = a.id')
			->where('tag_id = ?',$this->paramInput->id);

		/* 分页 */
		
		$count = $select->query()->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,"/productcp/tag/taglist?id={$this->paramInput->id}&&page={page}");
		$this->view->pagebar = $corepage->output();
		$this->view->count = $count;
		
		/* 列表 */
		
		$tagList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','t')
			->columns(array('product_name','status'),'p')
			->columns('tag_name','a')
			->order('order ASC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		$this->view->tagList = $tagList;
	}
	
	public function tageditAction()
	{
		if (!params($this))
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
					array(
							'href' => '/productcp/tag/list',
							'text' => '返回')
			));
		}

		if ($this->_request->isPost())
		{
			if (form($this))
			{
				/* 更新 */

				$this->rows['product_tagdata'] = $this->models['product_tagdata']->fetchRow(array('tag_id =?' => $this->paramInput->tagId,'product_id = ?' => $this->paramInput->productId));
				$this->rows['product_tagdata']->title = $this->input->title;
				$this->rows['product_tagdata']->image = $this->input->image;
				$this->rows['product_tagdata']->save();
				
				/* 提示 */

				$this->_helper->notice('编辑成功','','success',array(
						array(
								'href' => "/productcp/tag/taglist?id={$this->paramInput->tagId}",
								'text' => '返回')
				));
			}
		}
	}
	
	/**
	 *  添加
	 */
	public function addAction()
	{
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				$this->rows['product_tag'] = $this->models['product_tag']->createRow();
				
				/* 更新 */
				
				$this->rows['product_tag']->tag_name = $this->input->tag_name;
				$this->rows['product_tag']->save();
				
				/* 提示 */
				
				$this->_helper->notice('编辑成功','','success',array(
					array(
						'href' => "/productcp/tag/list",
						'text' => '返回')
				));
			}
		}
	}
	
	/**
	 *  编辑
	 */
	public function editAction()
	{
		if (!params($this)) 
		{
			/* 提示 */
			
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/productcp/tag/list',
					'text' => '返回')
			));
		}
		
		$this->rows['product_tag'] = $this->models['product_tag']->find($this->paramInput->id)->current();
		
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				/* 更新 */
				
				$this->rows['product_tag']->tag_name = $this->input->tag_name;
				$this->rows['product_tag']->save();
				
				/* 提示 */
				
				$this->_helper->notice('编辑成功','','success',array(
					array(
						'href' => "/productcp/tag/list",
						'text' => '返回')
				));
			}
		}
		
		if ($this->_request->isGet()) 
		{
			$this->data = $this->rows['product_tag']->toArray();
		}
	}
	
	/**
	 * ajax
	 */
	public function ajaxAction()
	{
		if (!$this->_request->isXmlHttpRequest())
		{
			exit ;
		}
		
		$op = $this->_request->getQuery('op','');
		$json = array();
		$this->_helper->viewRenderer->setNoRender();
		
		if (!ajax($this))
		{
			$json['flag'] = 'error';
			$json['msg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		switch ($op)
		{
			case 'order' :
				$this->rows['product_tagdata'] = $this->models['product_tagdata']->fetchRow(array('tag_id =?' => $this->input->tag,'product_id = ?' => $this->input->id));
				$this->rows['product_tagdata']->order = $this->input->order;
				$this->rows['product_tagdata']->save();
					
				$json['errno'] = '0';
				$this->_helper->json($json);
				break;
			
			/* 批量排序*/
			
			case 'orderlist':
				if (is_array($this->input->id))
				{
					$ids = implode(',', array_values($this->input->id));
					$sql = "UPDATE `product_tagdata` SET `order` = CASE `product_id` ";
					foreach ($this->input->id as $key => $id)
					{
						$sql .= sprintf("WHEN %d THEN '%d' ", $id, $this->input->order[$key]);
					}
					$sql .= "END WHERE `tag_id` = {$this->input->tag[0]} AND `product_id` in ($ids)";
					$db = Zend_Registry::get('db');
					$db->query($sql);

					$json['errno'] = 0;
					$this->_helper->json($json);
				}
				break;
				
			case 'delete':
				if (is_array($this->input->id))
				{
					foreach ($this->input->id as $key => $id)
					{
						$where = array('product_id = ?' => $id,'tag_id = ?' => $this->input->tag[$key]);
						$this->_db->delete('product_tagdata',$where);
					}
					$json['errno'] = 0;
					$this->_helper->json($json);
				}
				$where = array('product_id = ?' => $this->input->id,'tag_id = ?' => $this->input->tag);
				$this->_db->delete('product_tagdata',$where);
				$json['errno'] = 0;
				$this->_helper->json($json);
				
			case 'deletedown':
				//已下架产品
				$deleteInfo = $this->_db->select()
					->from(array('t' => 'product_tagdata'))
					->joinLeft(array('p' => 'product'), 't.product_id = p.id','status')
					->where('p.status = 3')
					->query()
					->fetchAll();
				if (empty($deleteInfo))
				{
					$json['errno'] = 1;
					$json['msg'] = '没有已下架产品标签';
					$this->_helper->json($json);
				}
				foreach ($deleteInfo as $info)
				{
					$where = array('product_id = ?' => $info['product_id'],'tag_id = ?' => $info['tag_id']);
					$this->_db->delete('product_tagdata',$where);
				}
				$json['errno'] = 0;
				$this->_helper->json($json);
			default:
				break;
		}
		$this->_helper->json($json);
	}
	
	/**
	 *  删除
	 */
	public function deleteAction()
	{
		/* 取消视图 */
		
		$this->_helper->viewRenderer->setNoRender();
		
		$json = array();
		
		if (!params($this)) 
		{
			if ($this->_request->isXmlHttpRequest()) 
			{
				$json['errno'] = 1;
				$json['errmsg'] = $this->error->firstMessage();
				$this->_helper->json($json);
			}
			else 
			{
				$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
					array(
						'href' => 'javascript:history.back();',
						'text' => '返回')
				));
			}
		}
		
		/* 删除商品 */
		
		$this->rows['product_tag'] = $this->models['product_tag']->find($this->paramInput->id)->current();
		$this->rows['product_tag']->status = -1;
		$this->rows['product_tag']->save();
		
		if ($this->_request->isXmlHttpRequest()) 
		{
			$json['errno'] = 0;
			$this->_helper->json($json);
		}
		else 
		{
			$this->_helper->notice('删除成功','','success',array(
				array(
					'href' => 'javascript:history.back();',
					'text' => '返回')
			));
		}
	}
}

?>