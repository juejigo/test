<?php

class Static_HtmlController extends Core2_Controller_Action_Fr 
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		$this->models['news'] = new Model_News();
		$this->models['news_data'] = new Model_NewsData();
		$this->models['news_cate'] = new Model_NewsCate();
	}
	
	/**
	 * 关于我们
	 */
	public function aboutusAction()
	{
	    if (!params($this))
	    {
	        $this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
	            array(
	                'href' => '/admincp',
	                'text' => '返回')
	        ));
	    }

	    if($this->paramInput->id != "")
	    {
	        $this->view->new_id = $this->paramInput->id;
	         
	        //查询新闻
	        $new = $this->_db->select()
    	        ->from(array('n' => 'news'))
    	        ->joinLeft(array('p' => 'news_data'),'n.id =p.news_id',array('content'))
    	        ->where('n.id = ?',$this->paramInput->id)
    	        ->query()
    	        ->fetch();
	    
	        $this->view->new = $new;
	        
	        /*seo*/
	        $this->view->headerTitle = $new['title'];
	        $this->view->headerKeywords = $new['title']." 友趣游";
	        $this->view->headerDescription =  $new['title'];
	        
	    }
	    
	    
	}
}

?>