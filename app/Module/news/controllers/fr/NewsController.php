<?php

class News_NewsController extends Core2_Controller_Action_Fr 
{
	 
 	/**
	 *  初始化
	 */
	public function init()
	{
		 parent::init();
	 
	}
	
	public function indexAction()
	{
		$this->_forward('list');
	}
	
	
	public function detailAction()
	{  

		if (!form($this)) 
		{ 
	        $this->_helper->notice('页面错误',$this->error->firstMessage(),'error_1',array(
				array(
					'href' => '/newsfr/news',
					'text' => '帮助中心')
			));
		}
		
		$news = array();
		$r = $select = $this->_db->select()
			->from(array('a' => 'news'))
			->joinLeft(array('d' => 'news_data'),'d.news_id = a.id',array('content'))
 	        ->joinLeft(array('c' => 'news_cate'),'c.id = a.cate_id',array('cate_name'))			
			->where('a.id = ?',$this->input->id)
			->query()
			->fetch();
			
	 	$news['title']    = $r['title'];
		$news['image']    = thumbpath($r['image']);
		$news['views']    = $r['views'];
		$news['cate_name']    = $r['cate_name'];		
		$news['dateline'] = $r['dateline'];
		$news['content'] =htmlspecialchars_decode(stripcslashes($r['content'])) ; 
		$this->_db->update('news',array('views' => new Zend_Db_Expr('views + 1')),array('id = ?' => $this->input->id)); 
		$cookie = new Zend_Http_Cookie('foo',$_SERVER['HTTP_REFERER'],time(),'/',$this->_config->site->domain);
 
		$this->view->newsdetail = $news; 		
	    $this->view->backurl = $cookie->getValue();
	}
	 
	public function listAction()
	{
	  
	   if (!params($this)) 
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error_1',array(
				array(
					'href' => '/newsfr/news',
					'text' => '返回')
			));
		} 
 	 	$newsList= $select = $this->_db->select()
		
							->from(array('n' => 'news')) 
							
						    ->joinLeft(array('c' => 'news_cate'),'n.cate_id = c.id',array('cate_name')) 
							
				 			->where('c.status = ?','1')
							
							->where('c.id = ?',$this->paramInput->id)
							
							->order('n.dateline DESC') 
							
							->query() 
							
							->fetchAll();  			 
		$this->view->newsList = $newsList;
	}
	  
}

?>